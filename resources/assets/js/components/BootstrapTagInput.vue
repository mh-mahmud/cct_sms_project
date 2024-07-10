<!-- https://github.com/yairEO/tagify -->
<template>
        <textarea style="display: none" class="form-control m-input"></textarea>
</template>

<script>
export default{
    data() {
        return {
        }
    },
    props:['remoteUrl','dataParams',"isRequired"],
    mounted() { 
        this.initTagInput(this.remoteUrl,this.dataParams);
    },
    methods: {
        initTagInput(remoteUrl, dataParams){ 
            var vueSelf = this;
            var attrId = jQuery('textarea').attr('id');
            var attrName = jQuery('textarea').attr('name');
            setTimeout(function(){ 
                var input = document.querySelector('#'+attrId),
                    tagify = new Tagify(input, {
                        whitelist:[],                                             
                        callbacks: {
                            add: function(e){ 
                                tagValidate(e);
                            }
                        }
                    }),
                    controller; // for aborting the call

                // listen to any keystrokes which modify tagify's input
                tagify.on('input', onInput)
                var modelName = dataParams.model;
                var initialData = dataParams.initialData;
                if(initialData){
                    setTimeout(function(){
                        console.log('setData');
                        tagify.addTags(vueSelf.$parent.initialData);
                    },3000);
                }              
                Vue.set(vueSelf.$parent, modelName ,tagify);
                function onInput(e){ 
                    // unset validation error
                    var errorBag = vueSelf.$parent.errors;
                    errorBag.remove(attrName);

                    var value = e.detail.trim();
                    if(typeof value !== 'undefined' && value.length >= 3){
                        tagify.settings.whitelist.length = 0; // reset the whitelist

                        controller && controller.abort();
                        controller = new AbortController();

                        // tagify.settings.addTagOnBlur = false;
                        
                        fetch(remoteUrl+'?q='+value,{signal:controller.signal})
                        .then(RES => RES.json())
                        .then(function(whitelist){ 
                            tagify.settings.whitelist = whitelist;
                            tagify.dropdown.show.call(tagify, value); // render the suggestions dropdown
                        })
                    }
                }
                function tagValidate(e){ 
                    var errorBag = vueSelf.$parent.errors;
                    var val = e.detail.data.code ? e.detail.data.code : e.detail.data.value;  
                    var length = val.length; 
                    if( !(length == 10 || (length == 11 && val.substr(0,1) == 1)) ){
                        tagify.removeTag(e.detail.data.value);
                        if(attrName && errorBag){
                            // set validation error
                            errorBag.add({
                                field: attrName,
                                msg: 'Input value is not valid. Ex: (555) 555-1234'
                            });
                        }
                    }
                }

            }, 2000);

        }
    }
}
</script>
