sudo apt-get update && sudo apt-get dist-upgrade && sudo apt-get autoremove

wget -q https://www.virtualbox.org/download/oracle_vbox_2016.asc -O- | sudo apt-key add -
wget -q https://www.virtualbox.org/download/oracle_vbox.asc -O- | sudo apt-key add -

sudo sh -c 'echo "deb http://download.virtualbox.org/virtualbox/debian $(lsb_release -sc) contrib" >> /etc/apt/sources.list'

sudo apt-get update
sudo apt-get install virtualbox-5.2

VBoxManage -v

wget https://releases.hashicorp.com/vagrant/2.0.1/vagrant_2.0.1_x86_64.deb
sudo dpkg -i vagrant_2.0.1_x86_64.deb

vagrant -v

vagrant box add laravel/homestead

git clone https://github.com/laravel/homestead.git ~/homestead

sudo apt install git

cd ~/homestead

git checkout v7.0.1
bash init.sh

ssh-keygen -t rsa -C "vagrant@homestead"

