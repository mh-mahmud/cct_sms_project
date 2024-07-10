<template>
    <div v-if="jsPlugin" class="container">
        <add-plugin-files :css-files="jsPlugin.css" :js-files="jsPlugin.js"></add-plugin-files>
    </div>
</template>


<script>
 
    export default{
        props:["jsPlugin"]
    };

    Vue.component('add-plugin-files', {
        data() {
            return {
            }
        },
        template: "<span></span>",
        props:['cssFiles','jsFiles'],
        mounted(){ 
            if(typeof this.cssFiles != 'undefined' && this.cssFiles.length){
                $.each(this.cssFiles, function(key, value) {
                    var path = BASE_URL+value; 
                    let link = document.createElement('link');
                    link.setAttribute('href', value);
                    link.setAttribute('type', "text/css");
                    link.setAttribute('rel', "stylesheet");
                    document.head.appendChild(link);
                });   
            }
            if(typeof this.jsFiles != 'undefined' && this.jsFiles.length){ 
                $.each(this.jsFiles, function(key, value) { 
                    var path = BASE_URL+value;                    
                    let script = document.createElement('script');
                    script.setAttribute('src', path);
                    document.head.appendChild(script);
                });   
            }    

        }
        
    });
 
</script>