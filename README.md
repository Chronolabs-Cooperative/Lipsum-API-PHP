# Chronolabs Cooperative 

## Lorem Ipsum Generations API ~ http://lipsum.snails.email

### Author: Simon Antony Roberts <wishcraft@users.sourceforge.net>

This API generates Lorem Ipsum from a well known feed for the use of generating real time embedded lorem ipsum for previews and generating examples online for themes and modules for the use of example data.

The following definitions are for lorem ipsum the text below are taken from https://www.lipsum.com/
 
## What is Lorem Ipsum?
Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.

## Why do we use it?
It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).

## Where does it come from?
Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.

The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.

## Where can I get some?
There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.

## Apache Module URL Rewrite

This goes in your API_ROOT/.htaccess after enabling the rewrite module with apache2

    php_value memory_limit 145M
    php_value upload_max_filesize 2M
    php_value post_max_size 2M
    php_value error_reporting 0
    php_value display_errors 0

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    RewriteRule ^v2/([a-z0-9]{32}).png$ ping.php?&id=$1&version=2 [L,NC,QSA]
    RewriteRule ^v2/(email).api$ $1.php?version=2 [L,NC,QSA]
    RewriteRule ^v2/(paragraphs|words|bytes|lists)/(any|lorem)/([0-9]+)/([0-9]+)/([a-z]+).api$ index.php?type=$1&start=$2&amount=$3&items=$4&output=$5&version=2 [L,NC,QSA]
    RewriteRule ^v1/(paragraphs|words|bytes|lists)/(any|lorem)/([0-9]+)/([0-9]+)/([a-z]+).api$ index.php?type=$1&start=$2&amount=$3&items=$4&output=$5&version=1 [L,NC,QSA]
