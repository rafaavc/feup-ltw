# To the Rescue!
Web Languages and Technologies project 2020/2021

## Running the local PHP web server
Due to using a custom PHP router, the server must be started this way:

```
./run
```
or
```
cd src
php -S localhost:4000 index.php
```

## Elements
- João Romão (201806779)
- Rafael Cristino (201806680)
- Xavier Pisco (201806134)

## Credentials (username / password)
- johnalewis / supersecure
- rafaavc / mypassword
- xamas / safest
- TsarkFC / marktsubasa
- bill9gates / bill9gates

## Features
### Security
- XSS: yes
- CSRF: yes
- SQL using prepare/execute: yes
- Passwords: bcrypt (php native hashing api with PASSWORD_DEFAULT)
- Data Validation: regex / php / html / javascript / ajax

### Technologies
- Separated logic/database/presentation: yes
- Semantic HTML tags: yes
- Responsive CSS: yes
- Javascript: yes
- Ajax: yes
- REST API: yes
- Custom Router: yes

## Usability
- Error/success messages: yes
- Forms don't lose data on error: yes
