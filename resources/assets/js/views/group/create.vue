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
                                    Add Group
                                </h3>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="m-form" @submit.prevent="addGroup">
                        <div class="m-portlet__body">
                            <div class="m-form__section m-form__section--first">
                                
                                <div class="form-group m-form__group row" :class="errors.has('name') || validationErrors.name ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="name">Name:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="Name" name="name" v-validate="'required'"  v-model="list.name" type="text" class="form-control m-input" placeholder="Enter Group Name">
                                        <span class="m-form__help" v-if="errors.has('name') || validationErrors.name">
                                            {{ errors.first('name') || validationErrors.name[0] }}
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
      list: {name: ''},
      message: "",     
      validationErrors: {},
      data:{}
    };
  },
  mounted(){
      this.create();
      this.bindCurrentRoute();
  },
  methods: {    
    create(){

        var url = 'api/groups/create';

        axios.get(url).then((res) => 
        { 
            this.data = res.data;
            this.$setDocumentTitle(this.data.title);
        })
        .catch(function (error) {
            console.log(error.response);
        });

    },
    // Add/Update Group
    addGroup() {
        this.$validator.validateAll().then((result) => { 
            if(result == true){
                if(typeof commonLib != 'undefined'){
                    commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
                }
                var vm = this;
                axios.post('api/groups', this.list).then((res) => 
                {
                    commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                    if(res.data.response_msg.type == 'success'){
                        this.list = {};                        
                        this.$router.push({name:'GroupList'});
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
