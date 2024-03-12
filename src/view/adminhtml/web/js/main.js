import { createApp } from 'vue'
import './assets/css/style.css'
import App from './vue/components/App.vue'

if (!window.gubeeIntegration) {
    window.gubeeIntegration = {}
}

document.addEventListener('DOMContentLoaded', function () {
    var doc = document.querySelector('.page-wrapper')
    if (doc.length === 0) {
        return
    }
    // replace element with a empty div
    doc.innerHTML = '<div id="app"></div>'
    createApp(App, {
        props: {
            configUrl: window.gubeeIntegration.settingsUrl,
            docsUrls: window.gubeeIntegration.docsUrls,
            storeUrl: "https://gubee.com.br/",
        }
    }).mount('#app')
})
