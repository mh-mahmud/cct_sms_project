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
                                    Edit Group
                                </h3>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="m-form" @submit.prevent="updateGroup">
                        <div class="m-portlet__body">
                            <div class="m-form__section m-form__section--first">
                                
                                <div class="form-group m-form__group row" :class="errors.has('name') || validationErrors.name ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="name">Name:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="Name" name="name" v-validate="'required'"  v-model="list.name" type="text" class="form-control m-input" placeholder="Enter List Name">
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
      list: {},
      message: "",     
      validationErrors: {},
      data:{}
    };
  },
  mounted(){
      if(this.$route.params.id){ 
          let id = this.$route.params.id;
          this.getGroupById(id);          
          this.bindCurrentRoute();
      }
  },
  
  methods: {    
    getGroupById(id) {
        let vm = this;
        var refUrl = BASE_URL+'admin-api/groups/'+id+'/edit';
        if(typeof commonLib != 'undefined'){
            commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
        }
        axios.get(refUrl).then((res) => 
        {
            this.list = res.data.data;
            this.data = res.data;
            this.message = res.data.message;
            this.$setDocumentTitle(this.data.title);
            commonLib.unblockUI(".m-content");
        })
        .catch(function (error) {
            console.log(error.response);
            commonLib.unblockUI(".m-content");
        });

    },
    // Add/Update Group
    updateGroup() {
        this.$validator.validateAll().then((result) => { 
            if(result == true){
                if(typeof commonLib != 'undefined'){
                    commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
                }
                var vm = this;
                this.list._method = 'PUT';
                axios.post(BASE_URL+'admin-api/groups/'+this.$route.params.id, this.list).then((res) => 
                {
                    commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                    if(res.data.response_msg.type == 'success'){
                        this.list = {};                        
                        this.$router.push({name:'AdminGroupList'});
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
