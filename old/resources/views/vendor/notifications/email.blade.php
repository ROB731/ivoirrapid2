@component('mail::message')
# Réinitialisation de votre mot de passe

Bonjour,

Nous avons bien reçu votre demande de réinitialisation de mot de passe pour votre compte sur **ivoirrapid.ci**. Pour sécuriser votre compte, veuillez cliquer sur le lien ci-dessous afin de choisir un nouveau mot de passe :

@component('mail::button', ['url' => $actionUrl])
Réinitialiser mon mot de passe
@endcomponent

Ce lien sera valide pendant **{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes**. 

Si vous n'êtes pas à l'origine de cette demande, nous vous invitons à ignorer cet e-mail. Votre mot de passe ne sera pas modifié sans votre intervention.

Si vous avez des questions ou besoin d'assistance, n'hésitez pas à nous contacter à **webmaster@ivoirrapid.ci**.

Cordialement,  
**L'équipe ivoirrapid**  
[www.ivoirrapid.ci](https://www.ivoirrapid.ci)

@endcomponent
