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
                                    Group Detail
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
                                                            <td>Name</td>                
                                                            <td>{{data.name}}</td>
                                                        </tr>
                                                        <tr>                            
                                                            <td>Total contacts</td>                
                                                            <td>
                                                                <router-link href="#"  v-bind:to="{name: 'ContactGroupList', params: {group_id:data.id}}" class="text-info" data-toggle="m-tooltip" title="Detail">
                                                                    {{ data.num_contacts }}
                                                                </router-link>
                                                            </td>
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
        this.getGroupDetailById(id);
        this.bindCurrentRoute();
    }
  },
  methods: {    
    getGroupDetailById(id) {
        if(typeof commonLib != 'undefined'){
            commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
        }
        let vm = this;
        var refUrl = 'api/group-detail/'+id;
        axios.get(refUrl).then((res) => 
        {
            //console.log(res);
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
