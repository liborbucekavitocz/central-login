<?php

/**
 * konfigurace jednotlivých slave webů
 */
return array(
    "slave" => array(
        "cz" => array(
            /**
             * apikey
             *
             * slouží jako jeden z klíču pro autentizaci na obou stranách komunikace
             *
             * vygenerovaný řetězec o 32 znacích
             */
            "apikey" => "6m7j4DUSs6uJFb73ntDUgnkoUHU5CK1j",

            /**
             * secret
             *
             * slouží jako jeden z klíču pro autentizaci na obou stranách komunikace
             *
             * vygenerovaný řetězec o 32 znacích
             */
            "secret" => "KxisP4ylYEtrkQUccjmYqzbnflvztXQ9",

            /**
             * url
             *
             * URL pro register, token a login operace na slave straně, kde je implemetováno zpracování dat
             */
            "url" => array(

                /**
                 * register
                 *
                 * URL slave aplikace pro registraci žádosti o přihlášení pomocí login a hesla
                 */
//                "register" => "https://cz.b2b.sinclair-solutions.com/auth/TY4KlDruBR0j/register/",
                "register" => "http://localhost/central-login-register.php?lang=cz",

                /**
                 * token
                 *
                 * URL slave aplikace pro registraci žádosti o přihlášení pomocí loginu a token2
                 */
//                "token" => "https://cz.b2b.sinclair-solutions.com/auth/TY4KlDruBR0j/token/",
                "token" => "http://localhost/central-login-token.php?lang=cz",

                /**
                 * login
                 *
                 * URL slave aplikace pro samotné přihlášení
                 */
//                "login" => "https://cz.b2b.sinclair-solutions.com/auth/TY4KlDruBR0j/login/",
                "login" => "http://localhost/central-login-login.php?lang=cz",
            ),

            /**
             * name
             *
             * název slave webu, který bude vystupovat na FE
             */
            "name" => "B2B sinclair-solutions.com CZ", // název (vypisuje se v dlaždici pro přihlášení do tohoto slave)

            /**
             * logo slave webu, které bude vystupovat na FE
             */
            "logo" => "cz/logo.png" // logo (vypisuje se v dlaždici pro přihlášení do tohoto slave)
        ),
        "en" => array(
            "apikey" => "0b2iTkTglLseSMvgfPoaWMwW0PkDGX0u",
            "secret" => "GqaRv3TEsYyqKfWUaC51VxMmPzQq5ol7",
            "url" => array(
//                "register" => "https://en.b2b.sinclair-solutions.com/auth/HFQcbfWLVQny/register/",
                "register" => "http://localhost/central-login-register.php?lang=en",
//                "token" => "https://en.b2b.sinclair-solutions.com/auth/HFQcbfWLVQny/token/",
                "token" => "http://localhost/central-login-token.php?lang=en",
//                "login" => "https://en.b2b.sinclair-solutions.com/auth/HFQcbfWLVQny/login/",
                "login" => "http://localhost/central-login-login.php?lang=en",
            ),
            "name" => "B2B sinclair-solutions.com EN",
            "logo" => "en/logo.png"
        ),
    )
);