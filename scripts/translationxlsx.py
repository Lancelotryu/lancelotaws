import os
import sys
import subprocess
from pathlib import Path

base_dir = Path(__file__).resolve().parent.parent

def update_translations():
    import pandas as pd
    from dotenv import load_dotenv
    from sqlalchemy import create_engine, Column, String, Text
    from sqlalchemy.orm import declarative_base, sessionmaker
    print("Charging .env...")
    load_dotenv(dotenv_path=base_dir / ".env")
    DB_URL = (
        f"mysql+mysqlconnector://{os.getenv('DB_USER')}:{os.getenv('DB_PASSWORD')}"
        f"@{os.getenv('DB_HOST')}:{os.getenv('DB_PORT')}/{os.getenv('DB_NAME')}"
    )
    
    print(f"Connect to database: {os.getenv('DB_NAME')}...")
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
        print("Failed to connect to database.")
        print(f"Error details : {e}")
        return
        
    print("Reading Excel file : translations.xlsx...")
    try:
        df = pd.read_excel(base_dir / "content" / "translations.xlsx")
        df['key'] = df[['Level 1', 'Level 2', 'Level 3', 'Level 4']].apply(
          lambda row: '.'.join(str(cell) for cell in row if pd.notna(cell) and str(cell).strip() != ''),
          axis=1
        )
    except Exception as e:
        print("Can't read Excel file.")
        print(f"Error details : {e}")
        return

    print("Inserting data in database...")
    insert_count = 0
    for _, row in df.iterrows():
        for lang in ['en', 'fr']:
            content = row.get(lang)
            if pd.notna(content):
                entry = Translation(lang=lang, key=row['key'], content=content)
                session.merge(entry)
                insert_count += 1
    session.commit()
    print(f"{insert_count} entries handled and saved.")


if __name__ == "__main__":
    update_translations()
    print("Script worked fine.")