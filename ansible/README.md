Dashboard - Ansible
======================

The included ansible playbook and role install the data portal dashboard web application along with required dependencies.

These files also serve as living documentation of the system requirements and configurations necessary to run the application.

This assume some familiarity with the Ansible configuration management system and that you have an ansible control machine configured. Detailed instructions for getting up and running on Ansible are maintained as part of our system-playbooks repository:

    https://github.com/City-of-Bloomington/system-playbooks

On the ansible control machine, make sure you have everything you need:

    git clone https://github.com/City-of-Bloomington/dashboard
    cd dashboard/ansible

Dependencies
-------------

Decide how you want to get the other necessary ansible roles:

    ansible-galaxy install --roles-path ./roles -r roles.yml

or for development:

```
git clone https://github.com/City-of-Bloomington/ansible-role-linux.git ./roles/City-of-Bloomington.linux
git clone https://github.com/City-of-Bloomington/ansible-role-apache.git ./roles/City-of-Bloomington.apache
git clone https://github.com/City-of-Bloomington/ansible-role-php.git ./roles/City-of-Bloomington.php
git clone https://github.com/City-of-Bloomington/ansible-role-fn1.git ./roles/City-of-Bloomington.fn1
git clone https://github.com/geerlingguy/ansible-role-composer ./roles/geerlingguy.composer
git clone https://github.com/geerlingguy/ansible-role-mysql ./roles/geerlingguy.mysql
etc
```

Variables
--------------

Variables are set and configured in a few different places. Update these to match your needs:

    roles/cob.dashboard/vars/main.yml
    group_vars/dashboard.yml

Vaulting (encrypting) any sensitive information is recommended. These variables should be in:

    group_vars/vault/dashboard.yml

More information about vaulting passwords is available here:

https://github.com/City-of-Bloomington/system-playbooks/tree/master/group_vars


Templates
--------------

It may be necessary to update the configuration file settings in the templates:

    roles/cob.dashboard/templates/*

Run the Playbook
-----------------

    ansible-playbook playbooks/dashboard.yml -i hosts.txt --ask-become-pass --ask-vault-pass

Additional Information
-------------------------
Did everything work as expected? If not, please let us know:

https://github.com/City-of-Bloomington/dashboard/issues

This project and others like it are maintained on the City of Bloomington's Github page:

https://github.com/city-of-bloomington

License
-------

Copyright (c) 2016 City of Bloomington, Indiana

This material is avialable under the GNU General Public License (GLP) v3.0:
https://www.gnu.org/licenses/gpl.txt


