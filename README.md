<img src="media/logoQuizManagerTransparent2.png"  width="350" height="250">

A flexible quiz repository for many question formats with a simple to learn input format and a easy to understand user interface. This projekt is done as a masters project of the THM (Technische Hochschule Mittelhessn).

## Overview: 
- [Live Deployment](#live-environment-demo)
- [Features](#features--distinctions)
- [Installation](#installation--deployment)
- [Technologies](#technologies)
- [Feature Work](#future-work--features)
- [Wiki](#futher-information--wiki)

---
---
## Live Environment (Demo):
There is an live deployment of the Programm where you can test the Quiz Manager. First of all you need to register yourself to be able to use the quiz manager. (In the Future will be able use a guest account to look around) <br>
**Link here: https://quiz.mnd.thm.de/quizVerwaltung**

On the landing page you find a lot of key features described in short. Under each of the diffrent sections you can also find a help page for further information. 

A direct Link to the help page is given here: **https://quiz.mnd.thm.de/quizVerwaltung/frontend/helpPage.php** <br> Also look here if you are interested in the input format.

---

## Features / Distinctions:
**Multilanguage**: <br>
The System is build to support more than one language. For now the complete ui supportes 3 different Languages (German, English, Spanish) and is easy expandable to support more. 

**On demand question Translation**: <br>
The platform uses machine translation to translate questions to different languages on demand of an user. Also the translation to a specific language only need to be done once, after that the user has the ability to just swap between the existing languages. There are 31 language supportet at the moment. For more information look [here](#technologies) or at the Wiki.

**Searching**: <br>
The system also features a free text search for specific questions, tags or even users. For questions the search uses matching of words or tags of the question. For users it searches for username, first name or last name. This simplifies looking for specific questions or questions from a user.

**Tagging**: <br>
Imported or already existing questions can be additionally assigned to tags. For this, the user has a selection of existing tags. This makes it easier to find these questions afterwards.



**Import questions**:
- Import different question types (**option / multioption / open / yes-no**)
- Import a lot of questions at once, using a easy to use import format (see: https://quiz.mnd.thm.de/quizVerwaltung/frontend/helpPage.php#importPosibilitys )
- Quick create and import single question using a easy webform

**Export questions**:
- Export a bunch of questions into different supported formats (**MoodleXML** (https://docs.moodle.org/401/en/Moodle_XML_format) **/ JSON / LaTex / simpQui** (see help page)) with one click.

**Edit questions**: <br>
The author of the question or an admin has the ability to edit or delete questions.

**Catalogs & shopping cart**: <br>
The user has the possibility to compile his own collections (catalogs) of questions. For this purpose, he has the possibility to select any questions from the repository and to place them in a shopping cart-like structure. From there he can either create a catalog that is saved in his profile or export these questions directly from the shopping cart in one of the above mentionted formats. Of course he can export this catalog later as well. 

**Karma System**:
The karma system is a kind of rating system for questions. Users can rate questions they like with an upvote or rate questions that are wrong or they don't like with a downvote. On the home page, questions are also sorted by karma.

**User Profile & Expience**:
Each user has his own profile on which, in addition to his details, other information such as his created questions or catalogs as well as followers, karma, etc. are found.
On the profiles there is the possibility to follow users. This means that the latest activities, such as new questions from users, are displayed on the start page.

**More to come**: <br>
These are the most of the key features of the Platform. For more Features explanation or Functions please refer to the Wiki

---
## Installation / Deployment:

---
## Technologies:
Die Technologien die für das Erstellen dieser Platform sind recht simple und sind nicht sehr viele. 

**Database**:
<img src="https://upload.wikimedia.org/wikipedia/commons/9/93/MongoDB_Logo.svg"  width="100" height="20"> <br>
Als Datenbank Nutzen wir MongoDB (NoSQL), für die Flexibilität einer NoSQL Datenbank, und da sich diese besser für unseren Anwendungszweck eigenet.

**Translation**:
<img src="https://upload.wikimedia.org/wikipedia/commons/e/ed/DeepL_logo.svg"  width="100" height="20"> <br>
Als Übersetzungs Tool nutzen wir die freie Version der DeepL Api (500k Zeichen). Diese stellt sich als besstes Tool heraus um den Context eines Textes (Frage) möglichst gut zu erhalten wenn sie in verschiedene Sprachen übersetzt werden.

**Languages**: <br>
<img src="https://upload.wikimedia.org/wikipedia/commons/2/27/PHP-logo.svg"  width="80" height="80">
<img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Javascript_badge.svg"  width="80" height="80">
<img src="https://upload.wikimedia.org/wikipedia/commons/d/d5/CSS3_logo_and_wordmark.svg"  width="80" height="80">
<img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Bootstrap_logo.svg"  width="80" height="80">

**Additional Info**: <br>
Additionaly we used some php extensions. For expample to use the mongoDB functions inside php. More can be found here [here](#futher-information--wiki)

---
## Future work & Features:
To Note the programm / platform isn't finished and we still have a lot of ideas to improve and expand the functionalies as well as adding new functions to the platform.

**Near Future**:

Adding <ins>*versioning capabilities*</ins> so that older versions of questions can be saved and restored in case of emergency.

<ins>*Expand on the Tag*</ins> functionalities. This means adding alot more Tags from which the user can choose from. Also enable the user to make requests for tags to be added to the platform.

<ins>*Expand the rolesystem*</ins> with an guest- and moderator account. The guest account can acces the startpage ans see all questions but doesn't have the ability to import or export questions etc.

Better and a lot more <ins>*Importfilter*</ins> so that we check the imports for hatespeech, swear words and more. Also to avoid duplicates of questions which are identical or are identical in the content.

Finally we want to <ins>*overhall some of the ui-elements and the style*</ins> of the platform to make it more accessible for all users and easier to use. 

**Distance Future**:

Adding <ins>*better search and filter*</ins> options and functionalities to imporve the user experience when searchin for specific questions types and or themes.

Expand the tagging system even further to <ins>*automatic tagging*</ins>. The idea is to use NLP tecniques to automaticcly assing tags to questions or to be able to suggest so determined tags for each question to the user.

Add <ins>*admin page*</ins> with own rights and overview of various statistics, as well as full access to database collections.

---
## Futher Information / Wiki: 
There exists also a Wiki via Github which can be used to get a more detailed look at the stukture of the programm an it's classes and files. <br>
The wiki is mainly intended to be used by developers. Since this contains information that should be rather uninteresting for most users of the platform and are not necessary for the use or installation of the software.

Wiki Link: **TODO**





 
