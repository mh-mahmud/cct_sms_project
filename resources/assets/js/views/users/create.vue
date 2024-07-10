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
                                    Create User
                                </h3>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="m-form" @submit.prevent="addUser">
                        <div class="m-portlet__body">
                            <div class="m-form__section m-form__section--first">
                                
                                <div class="form-group m-form__group row" :class="errors.has('first_name') || validationErrors.first_name ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="first_name">First Name:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="First Name" name="first_name" v-validate="'required|max:30'"  v-model="user.first_name" type="text" class="form-control m-input" placeholder="Enter First Name">
                                        <span class="m-form__help" v-if="errors.has('first_name') || validationErrors.first_name">
                                            {{ errors.first('first_name') || validationErrors.first_name[0] }}
                                        </span>
                                    </div>    
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('last_name') || validationErrors.last_name ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="last_name">Last Name:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="Last Name" name="last_name" v-validate="'required|max:30'"  v-model="user.last_name" type="text" class="form-control m-input" placeholder="Enter Last Name">
                                        <span class="m-form__help" v-if="errors.has('last_name') || validationErrors.last_name">
                                            {{ errors.first('last_name') || validationErrors.last_name[0] }}
                                        </span>
                                    </div>    
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('username') || validationErrors.username ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="username">Username:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="Username" name="username" v-validate="'required|max:50'"  v-model="user.username" type="text" class="form-control m-input" placeholder="Enter Username">
                                        <span class="m-form__help" v-if="errors.has('username') || validationErrors.username">
                                            {{ errors.first('username') || validationErrors.username[0] }}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('email') || validationErrors.email ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="email">Email:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="Email" name="email" v-validate="'required|email|max:60'"  v-model="user.email" type="email" class="form-control m-input" placeholder="example@gmail.com">
                                        <span class="m-form__help" v-if="errors.has('email') || validationErrors.email">
                                            {{ errors.first('email') || validationErrors.email[0] }}
                                        </span>
                                    </div>    
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('password') || validationErrors.password ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="password">Password:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="Password" name="password" v-validate="'required|min:6|max:32'"  v-model="user.password" type="password" class="form-control m-input" placeholder="Enter Password">
                                        <span class="m-form__help" v-if="errors.has('password') || validationErrors.password">
                                            {{ errors.first('password') || validationErrors.password[0] }}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('password_confirmation') || validationErrors.password_confirmation ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="password_confirmation">Confirm Password:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="Confirm Password" name="password_confirmation" v-validate="'required|min:6|max:32|is:'+user.password"  v-model="user.password_confirmation" type="password" class="form-control m-input" placeholder="Retype Password">
                                        <span class="m-form__help" v-if="errors.has('password_confirmation') || validationErrors.password_confirmation">
                                            {{ errors.first('password_confirmation') || validationErrors.password_confirmation[0] }}
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
      user: {},
      validationErrors: {},
      data:{}
    };
  },
  mounted(){
      this.create();
      this.bindCurrentRoute("UserCreate");
  },
  methods: {
    create(){
        var url = 'api/users/create';

        axios.get(url).then((res) => 
        { 
            this.data = res.data;
            this.$setDocumentTitle(this.data.title);
        })
        .catch(function (error) {
            console.log(error.response);
        });

    },
    // Add/Update User
    addUser() {
        this.$validator.validateAll().then((result) => { 
            if(result == true){
                if(typeof commonLib != 'undefined'){
                    commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
                }
                var vm = this;
                axios.post('api/users', this.user).then((res) => 
                {
                    this.user = {};
                    commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                    this.$router.push({name:'UserList'});
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
