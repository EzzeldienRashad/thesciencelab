﻿import { jsPDF } from "jspdf"
let callAddFont = function () {
this.addFileToVFS('ARIAL-normal.ttf', font);
this.addFont('ARIAL-normal.ttf', 'ARIAL', 'normal');
};
export {callAddFont};
jsPDF.API.events.push(['addFonts', callAddFont])