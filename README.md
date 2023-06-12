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

* Fetch remote json files
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

*admin:*
credentials: "include",
to 
//credentials!
-----------------------------------------

To see changes after modifying info move it to the other server
## If you downloaded this from the repo

* npm install

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
