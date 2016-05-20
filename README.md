[![Build Status](https://travis-ci.com/rodmarzavala/base.svg?token=vQ6m1DXVpCydKNuKAXfo&branch=master)](https://travis-ci.com/rodmarzavala/base)

# Symfony3 Base Project by Rodmar Zavala

A Symfony project created on January 22, 2016, 2:00 am.
This will be the base for all Symfony3 applications.

##Required


* PHP needs to be a minimum version of PHP 5.5.9
* JSON needs to be enabled
* ctype needs to be enabled
* Your php.ini needs to have the date.timezone setting

##Optional

* You need to have the PHP-XML module installed
* You need to have at least version 2.6.21 of libxml
* PHP tokenizer needs to be enabled
* mbstring functions need to be enabled
* iconv needs to be enabled
* POSIX needs to be enabled (only on *nix)
* Intl needs to be installed with ICU 4+
* APC 3.0.17+ (or another opcode cache needs to be installed)
* php.ini recommended settings
    * short_open_tag = Off
    * magic_quotes_gpc = Off
    * register_globals = Off
    * session.auto_start = Off

##Assetic
```
# Watch changes on assets
php bin/console assetic:watch

# Dump new assets
php bin/console assets:install --symlink
php bin/console assetic:dump
php bin/console cache:clear --env=prod
```


##Schema example

![Model](http://www.ens.ro/wp-content/uploads/2012/03/diagram.png)


```
# src/Ens/JobeetBundle/Resources/config/doctrine/Category.orm.yml
Ens\JobeetBundle\Entity\Category:
  type: entity
  table: category
  id:
    id:
      type: integer
      generator: { strategy: AUTO }
  fields:
    name:
      type: string
      length: 255
      unique: true
  oneToMany:
    jobs:
      targetEntity: Job
      mappedBy: category
    category_affiliates:
      targetEntity: CategoryAffiliate
      mappedBy: category
```

```
# src/Ens/JobeetBundle/Resources/config/doctrine/Job.orm.yml
Ens\JobeetBundle\Entity\Job:
  type: entity
  table: job
  id:
    id:
      type: integer
      generator: { strategy: AUTO }
  fields:
    type:
      type: string
      length: 255
      nullable: true
    company:
      type: string
      length: 255
    logo:
      type: string
      length: 255
      nullable: true
    url:
      type: string
      length: 255
      nullable: true
    position:
      type: string
      length: 255
    location:
      type: string
      length: 255
    description:
      type: text
    how_to_apply:
      type: text
    token:
      type: string
      length: 255
      unique: true
    is_public:
      type: boolean
      nullable: true
    is_activated:
      type: boolean
      nullable: true
    email:
      type: string
      length: 255
    expires_at:
      type: datetime
    created_at:
      type: datetime
    updated_at:
      type: datetime
      nullable: true
  manyToOne:
    category:
      targetEntity: Category
      inversedBy: jobs
      joinColumn:
        name: category_id
        referencedColumnName: id
  lifecycleCallbacks:
    prePersist: [ setCreatedAtValue ]
    preUpdate: [ setUpdatedAtValue ]
```

```
# src/Ens/JobeetBundle/Resources/config/doctrine/Affiliate.orm.yml
Ens\JobeetBundle\Entity\Affiliate:
  type: entity
  table: affiliate
  id:
    id:
      type: integer
      generator: { strategy: AUTO }
  fields:
    url:
      type: string
      length: 255
    email:
      type: string
      length: 255
      unique: true
    token:
      type: string
      length: 255
    created_at:
      type: datetime
  oneToMany:
    category_affiliates:
      targetEntity: CategoryAffiliate
      mappedBy: affiliate
  lifecycleCallbacks:
    prePersist: [ setCreatedAtValue ]
```

```
# src/Ens/JobeetBundle/Resources/config/doctrine/CategoryAffiliate.orm.yml
Ens\JobeetBundle\Entity\CategoryAffiliate:
  type: entity
  table: category_affiliate
  id:
    id:
      type: integer
      generator: { strategy: AUTO }
  manyToOne:
    category:
      targetEntity: Category
      inversedBy: category_affiliates
      joinColumn:
        name: category_id
        referencedColumnName: id
    affiliate:
      targetEntity: Affiliate
      inversedBy: category_affiliates
      joinColumn:
        name: affiliate_id
        referencedColumnName: id
```

##Generate Model and update DB
```
php bin/console doctrine:generate:entities WbcAdministratorBundle
php bin/console doctrine:schema:update --force
```

##Update translation XML
```
php bin/console translation:update --output-format="xlf" es app --dump-messages --force
```

##Build Bootstrap.cache
```
php vendor/sensio/distribution-bundle/Resources/bin/build_bootstrap.php
```


## Generate Bundle
```
php bin/console generate:doctrine:crud --overwrite --format="yml" --with-write WbcAdministratorBundle:Locale
```

## Import translations to LexikTranslationsBundle
```
php bin/console lexik:translations:import --globals --force
```

## Import Currency with data from ECB (European Central Bank)
```
php bin/console lexik:currency:import yahoo
```

## Git Folder renaming case
```
Renaming the folder to something like setup-temp
git add -A
git commit -m "Whate`ver"
Rename the folder back to what you want
git add -A
git commit -m "Whatever"
```


## Generate Migrations example
```
php app/console doctrine:generate:entities WbcAdministratorBundle
php app/console doctrine:migrations:diff
```

## RUN Migrations
```
php app/console doctrine:migrations:migrate
```