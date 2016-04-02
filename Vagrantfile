Vagrant.configure(2) do |config|

  # Access to host ~ folder.
  config.vm.synced_folder "~", "/home/vagrant/host"

  # And we setup an alias to the vm.
  config.vm.network :private_network, ip: "192.168.33.11"
  config.vm.hostname = "ubuntu.dev"

  # Boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "ubuntu/trusty64"

  config.vm.provider "virtualbox" do |v|
    v.memory = 1024
  end
end
