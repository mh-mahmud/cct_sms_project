<template>
  <div v-if="breadcrumbData != null" class="m-subheader ">
      <div class="d-flex align-items-center">
            <div class="mr-auto">
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--icon">
                        <a :href="baseUrl+'dashboard'" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                            <span class="breadcrumb-title">
                                Home <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    </li>

                    <li v-for="(val,index) in breadcrumbData.links" class="m-nav__item m-nav__item--icon">
                        <a :href="val.url"  class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la" :class="val.icon"></i>
                            <span class="breadcrumb-title">
                                {{val.name}} <i v-if="breadcrumbData.links.length != index+1" class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- modal buttons -->
            <div class="mr-1" v-if="breadcrumbData.modalButton.length" v-for="modalBtn  in breadcrumbData.modalButton">
                <a :href="modalBtn.url" class="btn" :class="modalBtn.class" :data-toggle="modalBtn.toggle" :data-target="modalBtn.target">
                    <span>
                    <i :class="modalBtn.icon"></i>
                    <span>{{modalBtn.name}}</span>
                    </span>
                </a>
                            
            </div>
            <!-- single buttons -->
            <div class="mr-1" v-if="breadcrumbData.singleButton.length" v-for="singleBtn  in breadcrumbData.singleButton">
                <a :href="singleBtn.url" class="btn" :class="singleBtn.class">
                    <span>
                    <i :class="singleBtn.icon"></i>
                    <span>{{singleBtn.name}}</span>
                    </span>
                </a>
                            
            </div>
            <!-- reload buttons -->
            <div class="mr-1" v-if="breadcrumbData.reloadButton.length" v-for="reloadBtn  in breadcrumbData.reloadButton">
                <a :href="reloadBtn.url" class="btn" :class="reloadBtn.class" @click="Reload()">
                    <span>
                    <i :class="reloadBtn.icon"></i>
                    <span>{{reloadBtn.name}}</span>
                    </span>
                </a>
                            
            </div>
            <!-- group buttons -->
            <div v-if="breadcrumbData.groupButton != null">
                <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover"
                    aria-expanded="true">
                    <a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                        <i class="la la-plus m--hide"></i>
                        <i class="la la-ellipsis-h"></i>
                    </a>
                    <div class="m-dropdown__wrapper">
                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                        <div class="m-dropdown__inner">
                            <div class="m-dropdown__body">
                                <div class="m-dropdown__content">
                                    <ul class="m-nav">
                                        <li v-for="groupButton  in breadcrumbData.groupButton" class="m-nav__item">
                                            <a :href="groupButton.url" class="m-nav__link" :class="groupButton.class">
                                                <i class="m-nav__link-icon" :class="groupButton.icon"></i>
                                                <span class="m-nav__link-text">{{groupButton.name}}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      </div>
  </div>
</template>
<script>
  export default{
    data(){
        return{
            baseUrl: BASE_URL
        }
    } , 
    props:["breadcrumbData"],
    methods : {
      Reload() {          
        this.$parent.Reload();
      }
    }
    
  }
</script>