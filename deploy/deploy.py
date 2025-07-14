#!/usr/bin/env python3

import os
os.environ["PYTHONPATH"] = "/home/ec2-user/.local/lib/python3.9/site-packages"
import site
site.addsitedir("/home/ec2-user/.local/lib/python3.9/site-packages")

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
USER_SITE   = "/home/ec2-user/.local/lib/python3.9/site-packages"
LOG_FILE    = Path("/home/ec2-user/deploy.log")

if not LOG_FILE.exists():
    LOG_FILE.touch()
    os.chmod(LOG_FILE, 0o666)  # lecture/écriture pour tous




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


# 4️⃣ Extraire directement dans le dossier final avec log détaillé et try/except
try:
    with zipfile.ZipFile(TMP_ZIP) as z:
        file_list = z.namelist()
        log(f"📂 {len(file_list)} fichiers à extraire depuis le zip")

        for filename in file_list:
            info = z.getinfo(filename)
            size_kb = info.file_size / 1024
            log(f"📄 Extraction : {filename} ({size_kb:.1f} KB)")
            z.extract(filename, WEB_ROOT)

    log("✅ ZIP extrait avec succès dans /www/portfolio")

except zipfile.BadZipFile:
    log("❌ Erreur : le fichier ZIP est corrompu ou invalide")
    exit(1)

except Exception as e:
    log(f"❌ Exception pendant l'extraction : {type(e).__name__} – {e}")
    exit(1)

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
env = os.environ.copy()
env["PYTHONPATH"] = f"/home/ec2-user/.local/lib/python3.9/site-packages:{env.get('PYTHONPATH', '')}"
env["PATH"] = f"/home/ec2-user/.local/bin:{env.get('PATH', '')}"

if SCRIPTS_DIR.exists():
    for script in sorted(SCRIPTS_DIR.glob("*.py")):
        log(f"🚀 Exécution : {script.name}")
        subprocess.run(
            ["python3", str(script)],
            check=True,
            env=env  # ← utilisez cet environnement modifié
        )
else:
    log("ℹ️ Aucun script trouvé dans /scripts")

# 1️⃣ Supprimer le ZIP temporaire
subprocess.run(["sudo", "rm", "-f", TMP_ZIP], check=False)
log("🧹 /tmp/site.zip supprimé")

log("🎉 Déploiement terminé")
