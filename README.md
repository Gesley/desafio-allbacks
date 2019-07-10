# Desafio Allbacks

- Desafio allbacks para p21 sistemas
- [Link](https://gist.github.com/p21sistemas/c66b07bb0c30de898642aeb0f9fdb4f0) - Link do desafio para entrevista

## Melhorias
 - atualiza a planilha com a data do dia.
 - se já existir um arquivo com a data do dia ele sera apagado.
 - verifica se o arquivo enviado é realmente .xml
 - desenvolvido no framework Laravel

## Manual de instalação

- É necessário fazer esses passos para que o código funcione

 * git clone do projeto
 * composer install 
 * abrir no terminal a pasta clonada e rodar o comando 'php artisan serve' para montar o servidor
 * opcionalmente pode ser utilizado outros servidores ( ex: Xamp, Mamp, etc )
 * adicionar a planilha na pasta public na raiz do projeto.

## Arquivos necessários
 * [Planilha excel](https://github.com/p21sistemas/skeleton21/blob/master/clientes.xlsx) - Planilha atualizada com a lista de clientes
 * [Arquivo XML](https://github.com/p21sistemas/skeleton21/blob/master/clientes.xml) - Arquivo XML para importação
 
## Rerquimentos

* Projeto
    - Apache/Nginx
    - PHP > 7
    - HTML/CSS
    - Framework Laravel 5.6+
    - PhpSpreadsheet

* Extenções obrigatórias
    - extension=fileinfo
    - extension=gd2    
    
## Duvidas/Sugestões

- contato: rodrigues.gesley@gmail.com
    