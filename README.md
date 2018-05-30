<h1>Todo-app</h1>
<h2>A simple todo-app done with Symfony4 (Doctrine, Twig...) and a little of VueJS</h2>

<h3>What is this?</h3>
<p>It is for learning purposes, just to learn Symfony4 and VueJS. It's an entire webapp, with a registration, login, profile and a TODO table.
The TODO table is done with VueJS with Ajax.</p>
<h3>Requirements</h3>
<p>A webserver with PHP7.2+ and a database manager like MYSQL. </p>
<h3>Installation</h3>
<ol>
    <li>Download or Git clone this repository => git clone https://github.com/amiguelc/todo-app.git</li>
    <li>Check requirements like MySQL and his extension for PHP (or PDO_MYSQL), and Symfony4 (module Rewrite, PHP7.2-xml)</li>
    <li>Rename env.dist and configure swiftmailer email and Doctrine database for dev environment. For prod you have to set the environmental variables on this file like <a href="https://symfony.com/doc/current/configuration/external_parameters.html#configuring-environment-variables-in-production">Symfony Docs</a></li>
    <li>Get Composer <a href="https://getcomposer.org/download/">https://getcomposer.org/download/</a>. Then run "composer install" and fix his errors. For prod; composer install --no-dev --optimize-autoloader</li>
    <li>Create database schema php bin/console doctrine:schema:create</li>
    <li>Configure webserver like <a href="https://symfony.com/doc/current/setup/web_server_configuration.html">Symfony Docs</a></li>
</ol>
<h3>Roadmap</h3>
<ol>
    <li>Translation</li>
    <li>Cache</li>
    <li>Units and functional tests</li>
    <li>Validation of data</li>
    <li>Some use of Vuex and Vue Router</li>
</ol>
<h3>License</h3>
<p>Free, use or fork it for commercial uses if you want.</p>