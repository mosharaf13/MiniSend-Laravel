
## MiniSend-Laravel
A mini version of [Mailersend](www.mailersend.com)

### installation
Create .env and Copy .env.example keys there
Sample Mail Configuration in .env

    MAIL_DRIVER=smtp
    MAIL_HOST=smtp.googlemail.com
    MAIL_PORT=465
    MAIL_USERNAME=samplemail@gmail.com
    MAIL_PASSWORD=
    MAIL_ENCRYPTION=ssl
    MAIL_FROM_ADDRESS=samplemail@gmail.com
    MAIL_FROM_NAME="${APP_NAME}"

Now go ahead and run below commands

    composer update
    ./vendor/bin/sail up -d 
    ./vendor/bin/sail aritsan key:generate
    ./vendor/bin/sail aritsan storage:link

The project should be up and running now.

### Available Apis
    POST /email

    {
        "from": "sample@demo.com",
        "to": "sample@demo.com",
        "subject": "sample subject",
        "text_content": "sample text content"
    }


    POST /email
    
    {
        "from": "sample@demo.com",
        "to": "sample@demo.com",
        "subject": "sample subject",
        "html_content": "<b>sample text content</b>"
    }

##Thoughts

During filtering on `GET /email` api I have used wildcard which is relatively slower and impractical on a production 
environment with large dataset. In that scenario we can make use of full text search engines like elasticsearch 
or algolia to make queries faster and still provide user's convenience to search on partial text.
