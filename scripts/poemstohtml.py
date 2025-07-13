import argparse
import os
import re
import mysql.connector
from datetime import datetime
from chardet import detect
from dotenv import load_dotenv
from pathlib import Path

base_dir = Path(__file__).resolve().parent.parent
load_dotenv(dotenv_path=base_dir / ".env")

# === ARGPARSE ===
parser = argparse.ArgumentParser(
    description="Import poems and comments into MySQL."
)
parser.add_argument(
    "-r", "--reset",
    action="store_true",
    help="erase (DROP) existing tables before importing"
)
args = parser.parse_args()


DOSSIER_POESIE = base_dir / "content" / "poems"
DOSSIER_COMMENTAIRES = DOSSIER_POESIE / "Commentaires"

MYSQL_CONFIG = {
    'host': os.getenv("DB_HOST"),
    'user': os.getenv("DB_USER"),
    'password': os.getenv("DB_PASSWORD"),
    'database': os.getenv("DB_NAME")

}


# === UTILITAIRES ===
def detect_encoding(fp):
    raw = fp.read_bytes()
    return detect(raw)["encoding"]

def read_file_utf8(fp):
    enc = detect_encoding(fp)
    return fp.read_text(encoding=enc, errors="replace").splitlines()

def normalize_text(text, typographie="fr"):
    # espaces / tabulations
    text = text.replace('\u202f',' ').replace('\u00a0',' ').replace('\t',' ')
    text = re.sub(r'\s{2,}',' ', text)
    # apostrophes courbes
    for c in ("’","‘","‛"): text = text.replace(c, "'")
    # tirets
    text = text.replace("----","—").replace("---","—").replace("--","—")
    # ellipses
    text = re.sub(r"\.{3,}", "…", text)
    text = text.replace("etc…","etc.")
    # guillemets
    text = text.replace("<<","« ").replace(">>"," »")
    # autres balises inline
    text = text.replace("+-+","—").replace("=+=","[…]")

    # ponctuation française/anglaise
    if typographie=="fr":
        text = re.sub(r'(?<! )([!?;:])', '\u202f\\1', text)
    else:
        text = re.sub(r'\s+([!?;:])', r'\1', text)

    # nettoyage de base
    text = text.strip()
    text = re.sub(r'<!--.*?-->','', text)
    text = re.sub(r'%.*$','', text)
    return text

# === CONVERSION HTML ===
def parse_italic(line):
    return re.sub(r'\*(.*?)\*', r'<i>\1</i>', line)

def parse_gras_initial(line):
    # balise ££ : première lettre alphabétique en <span class="gras">
    content = line[2:].strip()
    m = re.match(r'([^A-Za-z]*)([A-Za-z])(.+)', content)
    if m:
        b, l, a = m.groups()
        return f'{b}<span class="gras">{l}</span>{a}'
    return content

def ryu_to_html(lines):
    html = []
    block = []
    in_cit = False; cit_buf = []
    in_auth = False; auth_buf = []
    in_prose = False; prose_buf = []
    part_counter = 0

    def flush_block(cls="strophe"):
        if not block:
            return
        # enlève le dernier <br /> inutile
        if block[-1].endswith("<br />"):
            block[-1] = block[-1][:-6]
        html.append(f'<p class="{cls}">\n' + "\n".join(block) + "\n</p>")
        block.clear()

    for raw in lines:
        line = raw.strip()

        # --- Début bloc prose multi-lignes <=+ ... +=> ---
        if not in_prose and line.startswith("<=+"):
            in_prose = True
            # retire exactement "<=+" au début
            content = line[3:]
            # si fermeture sur la même ligne, on retire aussi "+=>"
            if content.endswith("+=>"):
                content = content[:-3]
                html.append(f'<p class="prose">{content.strip()}</p>')
                in_prose = False
            else:
                prose_buf = [content.strip()]
            continue

        # --- Accumulation / fermeture prose ---
        if in_prose:
            if line.endswith("+=>"):
                # on enlève les 3 derniers caractères "+=>"
                prose_buf.append(line[:-3].strip())
                joined = "<br />\n".join(prose_buf)
                html.append(f'<p class="prose">{joined}</p>')
                in_prose = False
                prose_buf = []
            else:
                prose_buf.append(line)
            continue

        # --- Début bloc citation multi-lignes <+ ... +> ---
        if not in_cit and line.startswith("<+") and not line.startswith("<++"):
            in_cit = True
            content = line[2:]
            if content.endswith("+>"):
                content = content[:-2]
                html.append(f'<p class="citation">« {content.strip()} »</p>')
                in_cit = False
            else:
                cit_buf = [content.strip()]
            continue

        # --- Accumulation / fermeture citation ---
        if in_cit:
            if line.endswith("+>"):
                cit_buf.append(line[:-2].strip())
                html.append(f'<p class="citation">« ' + "<br />\n".join(cit_buf) + ' »</p>')
                in_cit = False
                cit_buf = []
            else:
                cit_buf.append(line)
            continue

        # --- Auteur mono-ligne <++ ... ++> ---
        if line.startswith("<++") and line.endswith("++>"):
            auteur = line[3:-3].strip()
            html.append(f'<p class="auteurpoeme">{auteur}</p>')
            continue

        # --- Sections numérotées $> ---
        if line == "$>":
            flush_block()
            part_counter += 1
            roman = ["I","II","III","IV","V","VI","VII","VIII"][part_counter-1]
            html.append(f'<h4 class="partie">{roman}</h4>')
            continue

        # --- Titres ---
        if line.startswith('#####'):
            flush_block()
            txt = line.lstrip('#').strip()
            html.append(f'<h4 class="partie">{txt}</h4>')
            continue
        if line.startswith('#'):
            flush_block()
            title = line.lstrip('#').strip()
            html.append(f'<h3 class="poeme">{title}</h3>')
            continue

        # --- Séparateurs de strophe ---
        if line.startswith('>==='):
            flush_block("bigskip")
            continue
        if line.startswith('>=='):
            flush_block("medskip")
            continue
        if line.startswith('>='):
            flush_block()
            continue

        # --- Alinéas ---
        if line.startswith('>++++'):
            block.append(f'<span class="vindroite">{line[5:].strip()}</span><br />')
            continue
        if line.startswith('>+++'):
            block.append(f'<span class="vindeux">{line[4:].strip()}</span><br />')
            continue
        if line.startswith('>++'):
            block.append(f'<span class="vinun">{line[3:].strip()}</span><br />')
            continue
        if line.startswith('>+'):
            block.append(f'<span class="vinzero">{line[2:].strip()}</span><br />')
            continue

        # --- Première lettre en gras ££ ---
        if line.startswith('££'):
            txt = parse_gras_initial(line)
            block.append(txt + "<br />")
            continue

        # --- Contenu ordinaire avec italique inline ---
        block.append(parse_italic(line) + "<br />")

    # flush final
    flush_block()
    return "\n".join(html)




# === GÉNÉRATION DE L'ID_LOGIQUE ===
def generer_id_logique(f, recueil):
    code = recueil[:2]
    name = recueil[5:]
    abbr = ( re.sub(r"[^a-zA-Z]","",name.lower()) )[:4]
    base = f.stem.lower().replace(" ", "-")[:18]
    return f"{code}-{abbr}-{base}"

# === CRUD MySQL (tables déjà existantes) ===
def insert_poeme(cur, id_log, titre, rec, t, fich, html):
    cur.execute("""
        INSERT INTO poemes (id_logique,titre,recueil,type,fichier,contenu,date_import)
        VALUES (%s,%s,%s,%s,%s,%s,%s)
        ON DUPLICATE KEY UPDATE
          titre=VALUES(titre),
          recueil=VALUES(recueil),
          type=VALUES(type),
          contenu=VALUES(contenu),
          date_import=CURRENT_TIMESTAMP
    """, (id_log,titre,rec,t,fich,html,datetime.now()))

def insert_comment(cur, id_log, html):
    cur.execute("""
        INSERT INTO comments (id_logique,contenu,date_import)
        VALUES (%s,%s,%s)
    """, (id_log,html,datetime.now()))

def main():
    conn = mysql.connector.connect(**MYSQL_CONFIG)
    cur  = conn.cursor()

    # --- optionally DROP tables if -r was passed ---
    if args.reset:
        cur.execute("DROP TABLE IF EXISTS comments")
        cur.execute("DROP TABLE IF EXISTS poemes")

    # --- always ensure tables exist ---
    cur.execute("""
    CREATE TABLE IF NOT EXISTS poemes (
      id INT AUTO_INCREMENT PRIMARY KEY,
      id_logique VARCHAR(64) UNIQUE,
      titre VARCHAR(255),
      recueil VARCHAR(255),
      type CHAR(1),
      fichier VARCHAR(255) UNIQUE,
      contenu LONGTEXT,
      date_import DATETIME DEFAULT CURRENT_TIMESTAMP
    )""")
    cur.execute("""
    CREATE TABLE IF NOT EXISTS comments (
      id INT AUTO_INCREMENT PRIMARY KEY,
      id_logique VARCHAR(64),
      contenu LONGTEXT,
      date_import DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (id_logique) REFERENCES poemes(id_logique) ON DELETE CASCADE
    )""")

    # Poèmes
    for d in DOSSIER_POESIE.iterdir():
        if not d.is_dir() or d.name.startswith('--') or d.name=="Commentaires":
            continue
        rec = d.name.replace('µµ','<br />')
        for f in d.glob("*.ryu"):
            typ = "en" if f.stem.endswith("-en") else "fr"
            lines = [normalize_text(l,typ) for l in read_file_utf8(f) if l.strip()]
            html  = ryu_to_html(lines)
            titre = lines[0].lstrip('#').strip() if lines and lines[0].startswith('#') else f.stem
            idl   = generer_id_logique(f, d.name)
            insert_poeme(cur, idl, titre, rec, f.name[0], f.name, html)

    # Commentaires
    if DOSSIER_COMMENTAIRES.exists():
        for c in DOSSIER_COMMENTAIRES.glob("*.ryu"):
            lines = [normalize_text(l,"fr") for l in read_file_utf8(c) if l.strip()]
            html  = ryu_to_html(lines)
            key   = c.stem  # ex. "P01-aurore"
            cur.execute("SELECT id_logique FROM poemes WHERE id_logique LIKE %s", ('%'+key,))
            res = cur.fetchone()
            if res:
                insert_comment(cur, res[0], html)

    conn.commit()
    cur.close()
    conn.close()
    print("Done." if not args.reset else "Reset and done.")

if __name__ == "__main__":
    main()