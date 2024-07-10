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
                                    Schedule Detail
                                </h3>
                            </div>
                        </div>
                    </div>
                        <div class="m-portlet__body">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-12">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline collapsed" role="grid">
                                                    <tbody>
                                                        <tr>                            
                                                            <td>Schedule ID</td>                
                                                            <td>{{data.data.id}}</td>
                                                        </tr>
                                                        <tr>                            
                                                            <td>Schedule Time</td>                
                                                            <td>{{ data.data.start_time | formatDate('DD-MM-YYYY hh:mm A') }}</td>
                                                        </tr>
                                                        <tr>                            
                                                            <td>Time Zone</td>                
                                                            <td>{{data.data.time_zone}}</td>
                                                        </tr>
                                                        <tr>                            
                                                            <td>From</td>                
                                                            <td>{{data.data.sms_from}}</td>
                                                        </tr>
                                                        <tr>                            
                                                            <td>To</td>                
                                                            <td>                                                                
                                                                <span v-for="contact in data.data.contacts" class="badge">{{ contact.phone }}</span>

                                                                <span v-for="groupContact in data.data.groupContacts" class="badge">
                                                                    <router-link href="#"  v-bind:to="{name: 'GroupDetail', params: {id:groupContact.id}}" class="text-info" data-toggle="m-tooltip" title="Detail">
                                                                        {{ groupContact.name }}({{groupContact.num_contacts}})
                                                                    </router-link>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>                            
                                                            <td>Message Content</td>                
                                                            <td>
                                                                <span v-html="data.data.sms_text"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>                            
                                                            <td>Status</td>                
                                                            <td><span class="badge">{{ data.status[data.data.status] }}</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
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
        this.getScheduleById(id);
        this.bindCurrentRoute();
    }
  },
  methods: {
    getScheduleStatus : function(status){
            return this.$getScheduleStatus(status);
    },    
    getScheduleById(id) {
        if(typeof commonLib != 'undefined'){
            commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
        }
        let vm = this;
        var refUrl = 'api/schedule-detail/'+id;
        axios.get(refUrl).then((res) => 
        {
            //console.log(res);
            this.data = res.data;
            this.$setDocumentTitle(res.data.title);
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
