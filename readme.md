# Brazil Customer Attributes

![](https://imgur.com/pqTS38g.png)

![](https://imgur.com/vSACNr5)

![](https://imgur.com/CpuyDjM.png)

![](https://imgur.com/igfXu19.png)

![](https://i.imgur.com/vKqlkbD.png)

## About Module

Magento 2 module to adapt customer and address fields to brazil.

PS: This module doesn't work with checkout as guest.

### How to install

#### ✓ Install by Composer (recommended)
```
composer require systemcode/brazilcustomerattributes
php bin/magento module:enable
php bin/magento setup:upgrade
```

#### ✓ Install Manually
- Install [System Code Base](https://github.com/eduardoddias/Magento-SystemCode_Base) first 
- After copy module to folder app/code/SystemCode/BrazilCustomerAttributes and run commands:
```
php bin/magento setup:di:compile
php bin/magento setup:upgrade
```

### Configuration

Configure module on SystemCode > Brazil Customer Attributes > Configuration

### TODO
* Refactor
* Unity tests
* Login by attributes CPF/CNPJ
* Add mask for fields on admin
* One Step Checkout (future module)
* Add other zipcode consult methods

### Contribute
To contribute make project fork and an pull request or edit on Github.

### License
OSL-3.0

### Donators
* [Ricardo Martins](https://www.magenteiro.com/)

### Authors
* [Eduardo Diogo Dias](https://github.com/eduardoddias)


---


## Sobre o Módulo

Módulo em Magento 2 para adaptar os campos de usuário e endereço para o padrão brasileiro.

OBS: O módulo não funciona com checkout como visitante.

### Como Instalar

#### ✓ Instalação via Composer (recomendado)
```
composer require systemcode/brazilcustomerattributes
php bin/magento module:enable
php bin/magento setup:upgrade
```

#### ✓ Instalação Manual
- Install [System Code Base](https://github.com/eduardoddias/Magento-SystemCode_Base) first 
- After copy module to folder app/code/SystemCode/BrazilCustomerAttributes and run commands:
```
php bin/magento setup:di:compile
php bin/magento setup:upgrade
```

### Configuração
Configure o módulo em Lojas > Opções > Configurações > System Code > Atributos do Cliente do Brasil

### TODO
* Refatorar
* Testes unitários
* Login via CPF/CNPJ
* Adicionar máscaras no admin
* One Step Checkout (módulo futuro)
* Adicionar outros métodos de consulta de CEP

### Contribuir
Para contribuir faça um fork do projeto e depois um pull request ou edite através do Github.

### Licença
OSL-3.0

### Doadores
* [Ricardo Martins](https://www.magenteiro.com/)

### Autores
* [Eduardo Diogo Dias](https://github.com/eduardoddias)