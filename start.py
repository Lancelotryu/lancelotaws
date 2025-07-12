import os
import sys
import subprocess

# 📦 Étape 1 : installation automatique des dépendances
def install_requirements():
    print("📦 Vérification et installation des dépendances...")
    try:
        with open("requirements.txt") as f:
            requirements = f.read().splitlines()
        subprocess.check_call([sys.executable, "-m", "pip", "install", *requirements])
        print("✅ Dépendances installées.\n")
    except Exception as e:
        print(f"❌ Erreur pendant l'installation : {e}")
        sys.exit(1)

# 🧠 Étape 2 : chargement des données et mise à jour de la base
def update_translations():
    import pandas as pd
    from dotenv import load_dotenv
    from sqlalchemy import create_engine, Column, String, Text
    from sqlalchemy.orm import declarative_base, sessionmaker

    print("📄 Chargement de la configuration (.env)...")
    load_dotenv()
    DB_URL = (
        f"mysql+mysqlconnector://{os.getenv('DB_USER')}:{os.getenv('DB_PASSWORD')}"
        f"@{os.getenv('DB_HOST')}:{os.getenv('DB_PORT')}/{os.getenv('DB_NAME')}"
    )

    print(f"🔌 Connexion à la base de données : {os.getenv('DB_NAME')}...")
    try:
        engine = create_engine(DB_URL)
        Base = declarative_base()

        class Translation(Base):
            __tablename__ = 'translations'
            lang = Column(String(10), primary_key=True)
            key = Column(String(255), primary_key=True)
            content = Column(Text)

        Base.metadata.create_all(engine)
        Session = sessionmaker(bind=engine)
        session = Session()
    except Exception as e:
        print("❌ Échec de la connexion à la base de données.")
        print(f"Détail de l’erreur : {e}")
        return

    print("📊 Lecture du fichier Excel : translations.xlsx...")
    try:
        df = pd.read_excel("translations.xlsx")
        df['key'] = df[['Level 1', 'Level 2', 'Level 3', 'Level 4']].apply(
          lambda row: '.'.join(str(cell) for cell in row if pd.notna(cell) and str(cell).strip() != ''),
          axis=1
        )
    except Exception as e:
        print("❌ Erreur lors de la lecture du fichier Excel.")
        print(f"Détail de l’erreur : {e}")
        return

    print("📥 Insertion des données dans la base...")
    insert_count = 0
    for _, row in df.iterrows():
        for lang in ['en', 'fr']:
            content = row.get(lang)
            if pd.notna(content):
                entry = Translation(lang=lang, key=row['key'], content=content)
                session.merge(entry)
                insert_count += 1
    session.commit()
    print(f"✅ {insert_count} entrées traitées et enregistrées.")

# 🚀 Exécution principale
if __name__ == "__main__":
    install_requirements()
    update_translations()
    print("🎉 Script terminé avec succès.")