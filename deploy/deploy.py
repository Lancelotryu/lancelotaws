#!/usr/bin/env python3

import os
import subprocess
import shutil
import zipfile
from datetime import datetime
from pathlib import Path

# ─────────────── CONFIG ───────────────
BUCKET      = "lancelot-bucket99"
ZIP_KEY     = "site.zip"
TMP_ZIP     = "/tmp/site.zip"
DEPLOY_DIR  = Path("/home/ec2-user/www")
WEB_ROOT    = DEPLOY_DIR / "portfolio"
SCRIPTS_DIR = WEB_ROOT / "scripts"
REQUIREMENTS = WEB_ROOT / "requirements.txt"
USER_SITE   = "/home/ec2-user/.local/lib/python3.9/site-packages"
LOG_FILE    = Path("/home/ec2-user/deploy.log")

# ─────────────── LOGGING ───────────────
def log(msg: str):
    ts = datetime.utcnow().strftime("[%Y-%m-%d %H:%M:%S] ")
    print(ts + msg)
    LOG_FILE.parent.mkdir(parents=True, exist_ok=True)
    with LOG_FILE.open("a", encoding="utf-8") as f:
        f.write(ts + msg + "\n")

log("→ Démarrage du déploiement")

# 1️⃣ Supprimer le ZIP temporaire si présent
subprocess.run(["sudo", "rm", "-f", TMP_ZIP], check=False)
log("🧹 Ancien /tmp/site.zip supprimé")

# 2️⃣ Télécharger depuis S3
cmd_download = [
    "aws", "s3", "cp",
    f"s3://{BUCKET}/{ZIP_KEY}", TMP_ZIP,
    "--region", "eu-north-1"
]
subprocess.run(cmd_download, check=True)
log("✅ ZIP téléchargé depuis S3")

# 3️⃣ Supprimer l'ancien portfolio
if WEB_ROOT.exists():
    subprocess.run(["sudo", "rm", "-rf", str(WEB_ROOT)], check=True)
WEB_ROOT.mkdir(parents=True)
log("🧹 Ancien portfolio supprimé et dossier recréé")

# 4️⃣ Extraire directement dans le dossier final
with zipfile.ZipFile(TMP_ZIP) as z:
    z.extractall(WEB_ROOT)
log("✅ ZIP extrait dans /www/portfolio")

# 7️⃣ Restaurer le fichier .env après le déploiement
ENV_BACKUP = DEPLOY_DIR / ".env"

if ENV_BACKUP.exists():
    shutil.copy(ENV_BACKUP, WEB_ROOT / ".env")
    log("✅ .env restauré depuis le backup")
else:
    log("⚠️ Aucun .env de secours trouvé")

# 5️⃣ Installer les dépendances
if REQUIREMENTS.exists():
    log("📦 Installation des dépendances Python")
    subprocess.run(
        ["pip3", "install", "--user", "-r", str(REQUIREMENTS)],
        check=True
    )
else:
    log("ℹ️ Aucun requirements.txt trouvé")

# 6️⃣ Exécuter les scripts Python
if SCRIPTS_DIR.exists():
    for script in sorted(SCRIPTS_DIR.glob("*.py")):
        log(f"🚀 Exécution : {script.name}")
        subprocess.run(
            ["python3", str(script)],
            check=True,
            env={**os.environ, "PYTHONPATH": USER_SITE}
        )
else:
    log("ℹ️ Aucun script trouvé dans /scripts")

# 1️⃣ Supprimer le ZIP temporaire
subprocess.run(["sudo", "rm", "-f", TMP_ZIP], check=False)
log("🧹 /tmp/site.zip supprimé")

log("🎉 Déploiement terminé")
