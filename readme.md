# Brazil Customer Attributes

![alt tag](https://imgur.com/pqTS38g.png "Module Configurations")
![alt tag](https://i.imgur.com/vKqlkbD.png "New Account")
![alt tag](https://imgur.com/CpuyDjM.png "Edit Account")
![alt tag](https://imgur.com/igfXu19.png "New Address")
![alt tag](https://imgur.com/vSACNr5 "Checkout")

## About Module

Magento 2 module to adapt customer and address fields to brazil.

PS: This module doesn't work with checkout as guest.

### Instalação
Copy module to folder app/code/SystemCode/BrazilCustomerAttributes and run commands:
```
php bin/magento setup:di:compile
php bin/magento setup:upgrade
```
Configure module on Stores > Settings > Configuration > System Code > Brazil Customer Attributes

### TODO
* Refactor
* Unity tests
* Login by attributes CPF/CNPJ
* Add mask for fields on admin
* One Step Checkout (future module)
* Add module to Composer

### Contribuir
To contribute make project fork and an pull request or edit on Github.

### Licensa
OSL-3.0

### Autores
* [Eduardo Diogo Dias](https://github.com/eduardoddias)

## Sobre o Módulo

Módulo em Magento 2 para adaptar os campos de usuário e endereço para o padrão brasileiro.

OBS: O módulo não funciona com checkout como visitante.

### Instalação
Copie o módulo para a pasta app/code/SystemCode/BrazilCustomerAttributes e rode os seguintes comandos:
```
php bin/magento setup:di:compile
php bin/magento setup:upgrade
```
Configure o módulo em Lojas > Opções > Configurações > System Code > Atributos do Cliente do Brasil


### TODO
* Refatorar
* Testes unitários
* Login via CPF/CNPJ
* Adicionar máscaras no admin
* One Step Checkout (módulo futuro)
* Adicionar módulo no Composer

### Contribuir
Para contribuir faça um fork do projeto e depois um pull request ou edite através do Github.

### Licensa
OSL-3.0

### Autores
* [Eduardo Diogo Dias](https://github.com/eduardoddias)