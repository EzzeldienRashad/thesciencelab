export function removeDashes(str) {
    return str.charAt(0).toUpperCase() + str.slice(1).replaceAll("-", " ");
}