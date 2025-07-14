#!/bin/bash

echo "==== [1/6] Mise à jour du système ===="
sudo yum update -y

echo "==== [2/6] Installation de Python, pip et outils de compilation ===="
sudo yum install -y python3 python3-pip gcc gcc-c++ python3-devel mariadb105-server

echo "==== [3/6] Vérification et mise à jour de pip ===="
python3 -m pip install --upgrade pip --user

echo "==== [4/6] Création des dossiers avec les bons droits ===="
sudo mkdir -p /home/ec2-user/www/portfolio
sudo chown -R ec2-user:ec2-user /home/ec2-user/www

echo "==== [5/6] Installation des dépendances Python ===="
REQUIREMENTS_FILE="/home/ec2-user/www/portfolio/requirements.txt"

if [ -f "$REQUIREMENTS_FILE" ]; then
  python3 -m pip install --user -r "$REQUIREMENTS_FILE"
else
  echo "⚠️  Le fichier $REQUIREMENTS_FILE est introuvable."
  echo "Veuillez le placer d'abord dans le dossier portfolio."
  exit 1
fi

echo "==== [6/6] Configuration de l'environnement Python local ===="
PROFILE="/home/ec2-user/.bash_profile"
if ! grep -q ".local/bin" "$PROFILE"; then
  echo 'export PATH=$HOME/.local/bin:$PATH' >> "$PROFILE"
  echo "Ajout de ~/.local/bin au PATH."
fi
echo 'export PATH=$PATH:/root/.local/bin' >> /root/.bashrc
export PATH=$PATH:/root/.local/bin
sudo chown -R ec2-user:ec2-user /home/ec2-user

echo "✅ Installation terminée. Relancez la session ou tapez : source ~/.bash_profile"
