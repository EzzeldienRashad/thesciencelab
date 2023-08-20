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

* FETCH REMOTE JSON FILES !!!!!!!!!!!!!!!!!!!!!!!!!!
* Change "http://127.0.0.1" to "http://thesciencelab.byethost22.com" or "http://localhost"

*info:*
ini_set('session.cookie_samesite','None');
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
    $dsn = "mysql:host=localhost;dbname=b22_32993975_TheScienceLab;";
to
    $dsn = "mysql:host=sql205.byethost22.com;dbname=b22_32993975_TheScienceLab;";

-----------------------------------------

To see changes after modifying info move it to the other server
## If you downloaded this from the repo

* npm install
* configure remote htaccess to redirect requests to index html or admin
* copy info folder to the php server in local repo / upload info folder after build on host
* make a shortcut of todo in onedrive in local repo
* upload additional password.php with $password = "your password"
* create a database b22_32993975_TheScienceLab with table FailedLogins columns id date

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
