<template>
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <!-- js plugin -->
        <js-plugin :js-plugin="data.js_plugin"></js-plugin>
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
                                    Edit Campaign Profile
                                </h3>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="m-form" @submit.prevent="updateCampaign">
                        <div class="m-portlet__body">
                            <div class="m-form__section m-form__section--first">
                                
                                <div class="form-group m-form__group row" :class="errors.has('title') || validationErrors.title ? 'has-error' : ''">
                                    <label class="col-lg-2 col-form-label"  for="title">Title:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="Title" name="title" v-validate="'required|max:25'"  v-model="campaign.title" type="text" class="form-control m-input" placeholder="Campaign Title">
                                        <span class="m-form__help" v-if="errors.has('title') || validationErrors.title">
                                            {{ errors.first('title') || validationErrors.title[0] }}
                                        </span>
                                    </div>  
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('tag') || validationErrors.tag ? 'has-error' : ''">
                                    <label class="col-lg-2 col-form-label"  for="tag">Campaign Tag:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="Tag" name="tag" v-validate="'required|max:6|alpha_num'" v-model="campaign.tag" type="text" class="form-control m-input" placeholder="Campaign Tag">
                                        <span class="m-form__help" v-if="errors.has('tag') || validationErrors.tag">
                                            {{ errors.first('tag') || validationErrors.tag[0] }}
                                        </span>
                                    </div>    
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('email_template_id') || validationErrors.email_template_id ? 'has-error' : ''">
                                    <label class="col-lg-2 col-form-label"  for="email_template_id">Email Template:<span class="required">*</span></label>
                                    <div class="col-lg-6"> 
                                        <select class="form-control m-input" data-vv-as="Email Template" v-validate="'required'" name="email_template_id" v-model="campaign.email_template_id">
                                            <option value="">--Please Select--</option>
                                            <option v-for="(val, key) in data.emailTemplateList" v-bind:value="key">{{val}}</option>
                                        </select>
                                        <span class="m-form__help" v-if="errors.has('email_template_id') || validationErrors.email_template_id">
                                            {{ errors.first('email_template_id') || validationErrors.email_template_id[0] }}
                                        </span>
                                    </div>    
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('start_time') || validationErrors.start_time ? 'has-error' : ''">
                                    <label class="col-lg-2 col-form-label"  for="start_time">Start Time:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <date-picker data-vv-as="Start Time"  name="start_time" v-validate="'required|date_format:YYYY-MM-DD HH:mm'" v-model="campaign.start_time" :config="datepickerOpt" class="form-control m-input date-time-picker" placeholder="Start Time"></date-picker>
                                        <span class="m-form__help" v-if="errors.has('start_time') || validationErrors.start_time">
                                            {{ errors.first('start_time') || validationErrors.start_time[0] }}
                                        </span>
                                    </div>    
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('end_time') || validationErrors.end_time ? 'has-error' : ''">
                                    <label class="col-lg-2 col-form-label"  for="start_time">End Time:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <date-picker data-vv-as="End Time" name="end_time" v-validate="'required|date_format:YYYY-MM-DD HH:mm|after:'+campaign.start_time"  v-model="campaign.end_time" :config="datepickerOpt" class="form-control m-input date-time-picker" placeholder="End Time"></date-picker>   
                                        <span class="m-form__help" v-if="errors.has('end_time') || validationErrors.end_time">
                                            {{ errors.first('end_time') || validationErrors.end_time[0] }}
                                        </span>
                                    </div>    
                                </div>

                                <div class="form-group m-form__group row" :class="errors.has('from_email_name') || validationErrors.from_email_name ? 'has-error' : ''">
                                    <label class="col-lg-2 col-form-label"  for="from_email_name">From Email Name:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="From Email Name" name="from_email_name" v-validate="'required|max:255'"  v-model="campaign.from_email_name" type="text" class="form-control m-input" placeholder="From Email Name">
                                        <span class="m-form__help" v-if="errors.has('from_email_name') || validationErrors.from_email_name">
                                            {{ errors.first('from_email_name') || validationErrors.from_email_name[0] }}
                                        </span>
                                    </div>  
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('from_email') || validationErrors.from_email ? 'has-error' : ''">
                                    <label class="col-lg-2 col-form-label"  for="from_email">From Email:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="From Email" name="from_email" v-validate="'required|max:255'"  v-model="campaign.from_email" type="text" class="form-control m-input" placeholder="From Email">
                                        <span class="m-form__help" v-if="errors.has('from_email') || validationErrors.from_email">
                                            {{ errors.first('from_email') || validationErrors.from_email[0] }}
                                        </span>
                                    </div>  
                                </div>

                                <div class="form-group m-form__group row" :class="errors.has('from_email_subject') || validationErrors.from_email_subject ? 'has-error' : ''">
                                    <label class="col-lg-2 col-form-label"  for="from_email_subject">From Email Subject:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input data-vv-as="From Email Subject" name="from_email_subject" v-validate="'required|max:255'"  v-model="campaign.from_email_subject" type="text" class="form-control m-input" placeholder="From Email Subject">
                                        <span class="m-form__help" v-if="errors.has('from_email_subject') || validationErrors.from_email_subject">
                                            {{ errors.first('from_email_subject') || validationErrors.from_email_subject[0] }}
                                        </span>
                                    </div>  
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-lg-2 col-form-label" for="attachment">Attachment:</label>
                                    <div class="col-lg-6">
                                        <input name="attachment" type="file" id="attachment" ref="attachment" multiple="true" v-on:change="handleFileUpload()" class="form-control-file no-border">
                                    </div>
                                </div>
                                <div id="attachment-wrapper">
                                    <div v-bind:key="key" v-for="(val,key) in data.attachments" class="row pb-1">
                                        <div class="col-lg-6 col-md-6">
                                            {{val.filename}}
                                        </div>
                                        <div class="col-lg-2 col-md-2">
                                            <button @click.prevent="deleteAttachedFile(val.id, key)" data-toggle="m-tooltip" title="Delete" type="submit" class="btn btn-sm btn-danger">
                                                <i class="la la-trash"></i>
                                            </button>
                                        </div>
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
import AppComponent from '../../../components/AppComponent'
export default {
    extends: AppComponent,
    
  data() {
    return {
      campaign: {},
      datepickerOpt:{format: 'Y-MM-DD HH:mm',useCurrent: false,showClear: true,showClose: true},
      validationErrors: {},
      data:{},
      attachment:{}
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
    handleFileUpload(){ 
        this.attachment = this.$refs.attachment.files; 
    },
    // Update Campaign
    updateCampaign() {
        // Update
        this.$validator.validateAll().then((result) => { 
            if(result == true){
                if(typeof commonLib != 'undefined'){
                    commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
                }
                var vm = this;
                let formData = new FormData();
                if(this.$getLength(this.attachment)){
                    for( var i = 0; i < this.attachment.length; i++ ){
                        let file = this.attachment[i];
                        formData.append('attachment[' + i + ']', file);
                    }
                }
                
                formData.append('attachment', this.attachment);
                // add all model data to formData array
                $.each( this.campaign, function( key, value ) {
                    formData.append(key, value);
                });
                
                axios.post("api/campaign/update/"+this.$route.params.id, 
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then((res) => 
                { 
                    commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                    this.$router.push({name:'CampaignProfileList'});
                    commonLib.unblockUI(".m-content");
                })
                .catch(function (error) {
                    vm.validationErrors = error.response.data;
                    commonLib.unblockUI(".m-content");
                });
             }

        });    
      
    },
    getCampaignById(id) {
        let vm = this;
        var refUrl = 'api/campaign/'+id+'/edit';
        if(typeof commonLib != 'undefined'){
            commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
        }
        axios.get(refUrl).then((res) => 
        {
            this.data = res.data;
            this.campaign = res.data.data;
            this.$setDocumentTitle(this.data.title);
            commonLib.unblockUI(".m-content");
        })
        .catch(function (error) {
            console.log(error.response);
            commonLib.unblockUI(".m-content");
        });

    },
    /**
     * delete campaign profile attached file
     * @param fileId
     * @param index
     */
    deleteAttachedFile(fileId, index){
        var data = this.data.attachments;
        var url = 'api/campaign/delete-attachment/'+fileId;
        var msg = "Are you want to delete this file ?";
        this.$deleteDataItem(data, index, url, msg, "#attachment-wrapper");
    }

  }
};
</script>
