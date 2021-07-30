import Vue from "vue";
import VueFroala from 'vue-froala-wysiwyg'
import 'froala-editor/css/froala_editor.pkgd.min.css'
import 'froala-editor/css/froala_style.min.css'
Vue.use(VueFroala, {
    toolbarSticky: false,
    toolbarButtons: ['undo', 'redo' , '|', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'outdent', 'indent', 'clearFormatting', 'insertTable', 'html'],
    toolbarButtonsXS: ['undo', 'redo' , '-', 'bold', 'italic', 'underline']
})