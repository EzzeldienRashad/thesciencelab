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
# Important tips

## Before upload

* Fetch remote json files
* Change "http://127.0.0.1/htdocs" to "http://thesciencelab.byethost22.com"
* Change "header("Access-Control-Allow-Origin: http://localhost:5173");" to ""

## If you downloaded this from the repo

* npm install

## Hierarchy
App.vue:
|__HomePage
|  |__GradeLink -> GradeGames
|__GradeGames -> GameView
|__GameView
|  |__GameSettings
|  |__TheTest
|      |__TestQuestions
|      |   |__ChooseQuestions
|      |   |__RightOrWrongQuestions
|      |   |__CompleteQuestions
|      |__TestResults
|
|
|
|