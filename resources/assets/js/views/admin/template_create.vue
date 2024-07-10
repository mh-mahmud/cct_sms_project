<template>
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <js-plugin :js-plugin="data.js_plugin"></js-plugin>
        <breadcrumb :breadcrumb-data="data.breadcrumb"></breadcrumb>
        <div class="row m-content">
            <div class="col-lg-12">
                <!--begin::Portlet-->
                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon m--hide">
                                    <i class="la la-gear"></i>
                                </span>
                                <h3 class="m-portlet__head-text">
                                    Add Template
                                </h3>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="m-form" @submit.prevent="addTemplate">
                        <div class="m-portlet__body">
                            <div class="m-form__section m-form__section--first">
                                
                                <div class="form-group m-form__group row" :class="errors.has('name') || validationErrors.name ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="name">Name:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="Name" name="name" v-validate="'required'"  v-model="template.name" type="text" class="form-control m-input" placeholder="Enter Template Name">
                                        <span class="m-form__help" v-if="errors.has('name') || validationErrors.name">
                                            {{ errors.first('name') || validationErrors.name[0] }}
                                        </span>
                                    </div>    
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('message') || validationErrors.message ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="message">Message:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <textarea data-vv-as="Message" name="message" rows="4" cols="50" v-validate="'required|max:'+data.sms_text_size"  v-model="message" type="text" class="form-control m-input" placeholder="Enter Message" /></textarea>
                                        <span class="limiter">{{charactersLeft}} | {{partsLeft}} remaining</span>
                                        <span class="m-form__help" v-if="errors.has('message') || validationErrors.message">
                                            {{ errors.first('message') || validationErrors.message[0] }}
                                        </span>
                                    </div>    
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions m-form__actions">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Portlet-->

            </div>
        </div>
    </div>   
</template>

<script>
import AppComponent from '../../components/AppComponent'
export default {
  extends: AppComponent,
  data() {
    return {
      template: {},
      message: "",     
      validationErrors: {},
      data:{}
    };
  },
  computed: {
    charactersLeft() {
        var char = this.message.length,
            limit = this.data.sms_text_size;
        var remaining = limit - char;
          if(remaining < 0)
            remaining = 0;

        return "Characters: " + remaining + "/" + limit;
      },
      partsLeft() {
          var parts = this.message.length,limit = this.data.sms_text_part,part_size = this.data.sms_text_part_size;
          parts = Math.ceil(parts/part_size);
          var remaining = limit - parts;
          if(remaining < 0)
            remaining = 0;

        return "Parts: " + remaining + "/" + limit;
      }
  },
  mounted(){
      this.create();
      this.bindCurrentRoute();
  },
  methods: {    
    create(){

        var url = BASE_URL+'admin-api/templates/create';

        axios.get(url).then((res) => 
        { 
            this.data = res.data;
            this.$setDocumentTitle(this.data.title);
        })
        .catch(function (error) {
            console.log(error.response);
        });

    },
    // Add/Update Template
    addTemplate() {
        this.$validator.validateAll().then((result) => { 
            if(result == true){
                if(typeof commonLib != 'undefined'){
                    commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
                }
                var vm = this;
                //var text = this.template.message;
                //this.template.message = text.replace(/\r?\n/g, '<br />');
                this.template.message = this.message;
                axios.post(BASE_URL+'admin-api/templates', this.template).then((res) => 
                {
                    commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                    if(res.data.response_msg.type == 'success'){
                        this.template = {};
                        this.message = "";
                        this.$router.push({name:'AdminTemplateList'});
                    }
                    commonLib.unblockUI(".m-content");
                })
                .catch(function (error) {
                    vm.validationErrors = error.response.data;
                    commonLib.unblockUI(".m-content");
                });  
                 
            }

        }); 
      
    }
  }
};
</script>
