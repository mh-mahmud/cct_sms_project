<template>
    <div class="dataTables_wrapper">
      <div class="dataTables_paginate">
        <ul v-if="pagesNumber.length > 1" class="pagination"> 
          <li class="pagi-first-btn page-item" v-if="pagination.current_page > 1">
            <a class="page-link" href="javascript:void(0)" v-on:click.prevent="changePage(1)">
              First
            </a>
          </li>
          <li class="paginate_button page-item previous" v-if="pagination.current_page > 1">
            <a class="page-link" href="javascript:void(0)" aria-label="Previous" v-on:click.prevent="changePage(pagination.current_page - 1)">
              <i class="la la-angle-left"></i>
            </a>
          </li>
          <!-- page links -->
          <li class="paginate_button page-item" v-for="page in pagesNumber" :class="{'active': page == pagination.current_page}">
              <a class="page-link" href="javascript:void(0)" v-on:click.prevent="page != pagination.current_page ? changePage(page) : '' ">{{ page }}</a>
          </li>
          <!-- page links -->
          <li class="paginate_button page-item next" v-if="pagination.current_page < pagination.last_page">
              <a class="page-link" href="javascript:void(0)" aria-label="Next" v-on:click.prevent="changePage(pagination.current_page + 1)">
                  <i class="la la-angle-right"></i>
              </a>
          </li>
          <li class="pagi-last-btn page-item" v-if="pagination.current_page < pagination.last_page">
            <a class="page-link" href="javascript:void(0)" v-on:click.prevent="changePage(pagination.last_page)">
              Last
            </a>
          </li>
        </ul>
      </div>
    </div>
   
</template>
<script>
  export default{
      props: {
      pagination: {
          type: Object,
          required: true
      },
      offset: {
          type: Number,
          default: 4
      }
    },
    computed: {
      pagesNumber() { 
        if (!this.pagination.to) {
          return [];
        }
        let from = this.pagination.current_page - this.offset;
        if (from < 1) {
          from = 1;
        }
        let to = from + (this.offset * 2);
        if (to >= this.pagination.last_page) {
          to = this.pagination.last_page;
        }
        let pagesArray = [];
        for (let page = from; page <= to; page++) {
          pagesArray.push(page);
        } 
          return pagesArray;
      }
    },
    methods : {
      changePage(page) {
        this.pagination.current_page = page;
        this.$emit('paginate');
      }
    }
  }
</script>