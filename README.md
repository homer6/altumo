Overview
------------
Altumo is a library of tools for web development under the MIT License.

It currenly contains source code that includes:
    - php classes
    - linux bash scripts
   
In future versions, we plan for it to contain:   
    - javascript components based in google closure
    - javascript services based in node.js
    - c and c++ classes and functions

Dependencies
------------

   - PHP 5.3.4
     - Extensions:
       - CURL
       - mongodb client
   - phpunit 3.5.11 (if you want to run unit tests)
   - "Altumo Packages" require mongodb 1.2+
   - Classes under the Symfony_1_4 directory depend on Symfony 1.4

Installation
------------

   - git clone git://github.com/homer6/altumo.git
   - cd altumo
   - git submodule update --init --recursive

Testing
------------

   - pear channel-discover pear.phpunit.de
   - pear channel-discover components.ez.no
   - pear channel-discover pear.symfony-project.com
   - pear install phpunit/PHPUnit
   - ./test.sh
   

Sample Usage
------------

    **See the markdown files for specific usages for each library component.

    require_once( __DIR__ . '/loader.php' );
    $http_client = new \Altumo\Http\OutgoingHttpRequest('http://www.google.com');
    $response = $http_client->send();
    
    
    