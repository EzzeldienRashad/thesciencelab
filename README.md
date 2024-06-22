# TheScienceLab

This template should help get you started developing with Vue 3 in Vite.

## Recommended IDE Setup

[VSCode](https://code.visualstudio.com/) + [Volar](https://marketplace.visualstudio.com/items?itemName=Vue.volar) (and disable Vetur) + [TypeScript Vue Plugin (Volar)](https://marketplace.visualstudio.com/items?itemName=Vue.vscode-typescript-vue-plugin).

## Customize configuration

See [Vite Configuration Reference](https://vitejs.dev/config/).

## Project Setup

```sh
npm install
```

### Compile and Hot-Reload for Development

```sh
npm run dev
```

### Compile and Minify for Production

```sh
npm run build
```
------------------------------
# Important tips:

# Before upload

* FETCH REMOTE info/images FILES !!!!!!!!!!!!!!!!!!!!!!!!!!
* Change "http://127.0.0.1/thesciencelab" to "https://thesciencelab.infinityfreeapp.com" or "http://localhost"

*info:*
ini_set('session.cookie_samesite','None');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
if (isset($_SERVER["HTTP_ORIGIN"])) {
    $origin = $_SERVER["HTTP_ORIGIN"];
    if ($origin == "http://localhost:5173" || $origin == "http://localhost:5174") {
        header("Access-Control-Allow-Origin: " . $origin);
    }
}
header("Access-Control-Allow-Credentials: true");
to
//httpallow!

and
    $dsn = "mysql:host=localhost;dbname=if0_36665133_TheScienceLab;charset=utf8;";
to
    $dsn = "mysql:host=sql200.infinityfree.com;dbname=if0_36665133_TheScienceLab;charset=utf8;";

-----------------------------------------

To see changes after modifying info move it to the other server
## If you downloaded this from the repo

* npm install
move folder to apache localhost
* configure remote htaccess to redirect requests to index html or admin
* copy info folder to the php server in local repo / upload info folder after build on host
* make a shortcut of todo in onedrive in local repo
* upload additional password.php with $password = "database password" to info/functions and password.js with export default "your control panel login password" to admin root
* create a database if0_36665133_TheScienceLab with table FailedLogins columns id date username and table Members with columns id name username phone subject password

## Hierarchy
### main
App.vue:
|__HomePage
|   |__GradeLink -> GradeGames
|__GradeGames -> GameView
|__GameView
|   |__GameSettings
|   |__TheTest
|      |__TestQuestions
|      |   |__ChooseQuestions
|      |   |__RightOrWrongQuestions
|      |   |__CompleteQuestions
|      |__TestResults
### admin
App.vue:
|__LoginView
|__HomeView
|__GradeGames
|__ControlPanel
|   |__GradeUnits
|   |__ControlPanelChoose
|   |__ControlPanelComplete
|   |__ControlPanelRightOrWrong
