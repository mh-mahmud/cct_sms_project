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
                                    Campaign Profile Detail
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="row">
                                                <label class="control-label col-md-4">
                                                    <b>Title:</b>
                                                </label>
                                                <div class="col-md-8">
                                                    {{campaign.title}}                    
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="control-label col-md-4">
                                                    <b>Start Time:</b>
                                                </label>
                                                <div class="col-md-8">
                                                    {{campaign.start_time | formatDate('DD MMM YYYY hh:mm A')}}                       
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="control-label col-md-4">
                                                    <b>From Email Name:</b>
                                                </label>
                                                <div class="col-md-8">
                                                    {{campaign.from_email_name}}                       
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="control-label col-md-4">
                                                    <b>Email Template:</b>
                                                </label>
                                                <div v-if="data.emailTemplateList" class="col-md-8">
                                                    {{data.emailTemplateList[campaign.email_template_id]}}                       
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="control-label col-md-4">
                                                    <b>From Email Subject:</b>
                                                </label>
                                                <div class="col-md-8">
                                                    {{campaign.from_email_subject}}                       
                                                </div>
                                            </div>
                                            <div v-if="campaign.last_err_email" class="row">
                                                <label class="control-label col-md-4">
                                                    <b>Last Error Email:</b>
                                                </label>
                                                <div class="col-md-8">
                                                    {{campaign.last_err_email}}                       
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            
                                            <div class="row">
                                                <label class="control-label col-md-4">
                                                    <b>Campaign Tag:</b>
                                                </label>
                                                <div class="col-md-8">
                                                    {{campaign.tag}}                       
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="control-label col-md-4">
                                                    <b>End time:</b>
                                                </label>
                                                <div class="col-md-8">
                                                    {{campaign.end_time | formatDate('DD MMM YYYY hh:mm A')}}                       
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="control-label col-md-4">
                                                    <b>From Email:</b>
                                                </label>
                                                <div class="col-md-8"> 
                                                    {{campaign.from_email}}                       
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="control-label col-md-4">
                                                    <b>Status:</b>
                                                </label>
                                                <div v-if="data.campStatus" class="col-md-8">
                                                    {{data.campStatus[campaign.status]}}                       
                                                </div>
                                            </div>
                                            <div v-if="campaign.last_err_msg" class="row">
                                                <label class="control-label col-md-4">
                                                    <b>Last Error Message:</b>
                                                </label>
                                                <div class="col-md-8 break-word">
                                                    {{campaign.last_err_msg}}                       
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
                <div v-if="getLength(data.attachments) > 0" class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon m--hide">
                                    <i class="la la-gear"></i>
                                </span>
                                <h3 class="m-portlet__head-text">
                                    Campaign Attachments
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-body">
                                    <ul v-bind:key="key" v-for="(val,key) in data.attachments">
                                        <li>
                                            {{val.filename}}
                                        </li>
                                    </ul>   
                                </div>
                            </div>
                        </div> 
                    </div>
                    
                </div>

                <!--begin::Summary Portlet-->
                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon m--hide">
                                    <i class="la la-gear"></i>
                                </span>
                                <h3 class="m-portlet__head-text">
                                    Campaign Summary
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div v-if="data.leadStatuSummary" class="form-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="row">
                                                <label class="control-label col-md-10">
                                                    <b>Valid Email:</b>
                                                </label>
                                                <div class="col-md-2">
                                                    {{campaign.valid_emails}}                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="row">
                                                <label class="control-label col-md-10">
                                                    <b>Invalid Email </b>(During Csv Upload):
                                                </label>
                                                <div class="col-md-2">
                                                    {{campaign.invalid_emails}}                       
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="row">
                                                <label class="control-label col-md-10">
                                                    <b>Invalid Email </b>(During Processing):
                                                </label>
                                                <div class="col-md-2">
                                                    {{data.leadStatuSummary['7']}}                
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        
                                    </div>    
                                    <div class="row"> 
                                        <div class="col-lg-4">
                                            <div class="row">
                                                <label class="control-label col-md-10">
                                                    <b>Send:</b>
                                                </label>
                                                <div class="col-md-2">
                                                    {{campaign.send}}                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="row">
                                                <label class="control-label col-md-10">
                                                    <b>Unsend:</b>
                                                </label>
                                                <div class="col-md-2">
                                                   {{data.leadStatuSummary['0']}}                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="row">
                                                <label class="control-label col-md-10">
                                                    <b>Error:</b>
                                                </label>
                                                <div class="col-md-2">
                                                   {{data.leadStatuSummary['8']}}                        
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>    
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="row">
                                                <label class="control-label col-md-10">
                                                    <b>Select For Processing:</b>
                                                </label>
                                                <div class="col-md-2">
                                                    {{data.leadStatuSummary['9']}}                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                    
                </div>
                <!--end::Summary Portlet-->

            </div>
        </div>
    </div>   
</template>

<script>
import AppComponent from '../../../components/AppComponent'
export default {
    extends: AppComponent,
    
  data() {
    return {
      campaign: {},
      data: {},
    };
  },
  mounted(){
    if(this.$route.params.id){ 
        let id = this.$route.params.id;
        this.getCampaignById(id);
        this.bindCurrentRoute("CampaignProfileList");
    }
  },
  methods: {
    getCampaignById(id) {
        let vm = this;
        var refUrl = 'api/campaign/'+id;
        if(typeof commonLib != 'undefined'){
            commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
        }
        axios.get(refUrl).then((res) => 
        {
            this.campaign = res.data.data;
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
