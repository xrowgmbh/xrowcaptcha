<?php /* #?ini charset="utf-8"?

[RegionalSettings]
TranslationExtensions[]=xrowcaptcha

[SiteAccessSettings]
AnonymousAccessList[]=xrowcaptcha/generate

[RoleSettings]
PolicyOmitList[]=xrowcaptcha/generate

[Event]
Listeners[]=request/input@xrowCaptchaInputEvent::input

*/ ?>