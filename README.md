# lancelotaws
Pipeline from local computer to AWS cloud


```bash
# 1 : cloner le dépôt
git clone git@github.com:Lancelotryu/portfolio-aws.git
cd portfolio-aws

# 2 : variables d’environnement
cp .env.example .env
# <-- remplis les valeurs sensibles !

# 3 : environnement Python
python -m venv venv
source venv/bin/activate          # Windows : venv\Scripts\activate
pip install -r scripts/requirements.txt

# 4 : lancer l’import XLSX
python scripts/import_xlsx.py
