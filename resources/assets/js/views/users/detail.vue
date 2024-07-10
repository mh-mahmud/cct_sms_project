<template>
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <!-- BreadCrumb-->
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
                                    User Detail
                                </h3>
                            </div>
                        </div>
                    </div>
                        <div class="m-portlet__body">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-12">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <label class="control-label col-md-4">
                                                        <b>First Name:</b>
                                                    </label>
                                                    <div class="col-md-8">
                                                        {{user.first_name}}                    
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label class="control-label col-md-4">
                                                        <b>Username:</b>
                                                    </label>
                                                    <div class="col-md-8">
                                                        {{user.username}}                       
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label class="control-label col-md-4">
                                                        <b>Type:</b>
                                                    </label>
                                                    <div v-if="data.userType" class="col-md-8">
                                                        {{data.userType[user.type]}}                       
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                
                                                <div class="row">
                                                    <label class="control-label col-md-4">
                                                        <b>Last Name:</b>
                                                    </label>
                                                    <div class="col-md-8">
                                                        {{user.last_name}}                       
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label class="control-label col-md-4">
                                                        <b>Email:</b>
                                                    </label>
                                                    <div class="col-md-8">
                                                        {{user.email}}                       
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label class="control-label col-md-4">
                                                        <b>Status:</b>
                                                    </label>
                                                    <div v-if="data.userStatus" class="col-md-8">
                                                        {{data.userStatus[user.status]}}                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </div>    

                        </div>
                    
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
      user: {},
      data: {},
    };
  },
  mounted(){
    if(this.$route.params.id){ 
        let id = this.$route.params.id;
        this.getUserById(id);
        this.bindCurrentRoute("UserList");
    }
  },
  methods: {
    getUserById(id) {
        if(typeof commonLib != 'undefined'){
            commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
        }
        let vm = this;
        var refUrl = 'api/users/'+id;
        axios.get(refUrl).then((res) => 
        {
            this.user = res.data.data;
            this.data = res.data;
            this.$setDocumentTitle(this.data.title);
            commonLib.unblockUI(".m-content");
        })
        .catch(function (error) {
            console.log(error.response);
            commonLib.unblockUI(".m-content");
        });

    }

  }
};
</script>
