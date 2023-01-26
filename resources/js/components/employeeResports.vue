<template>
  <v-app class="no_height">

    <v-card
      :max-width="maxWidth ? 900 : 448"
      class="mx-auto"
      color="grey-lighten-3"
    >
    <v-alert class="alert" v-show="alertBtn"
      density="comfortable"
      :type="alertType"
    >
      {{ alertText }}
    </v-alert>
      <v-layout>
        <v-app-bar
          color="teal-darken-4"
          image="https://picsum.photos/1920/1080?random"
        >
          <template v-slot:image>
            <v-img
              gradient="to top right, rgba(19,84,122,.8), rgba(128,208,199,.8)"
            ></v-img>
          </template>



          <v-app-bar-title>Employees</v-app-bar-title>

          <v-spacer></v-spacer>

          <v-text-field
            v-if="searchBtn"
            transition="scroll-x-reverse-transition"
            :loading="loading"
            v-model="search"
            density="compact"
            variant="solo"
            label="Search templates"
            append-inner-icon="mdi-close-circle-outline"
            single-line
            hide-details
            @click:append-inner="(search = ''), (searchBtn = false)"
          ></v-text-field>
          <v-btn v-else @click="searchBtn = true" icon>
            <v-icon>mdi-account-search-outline</v-icon>
          </v-btn>

         <!-- dialog for creating employee -->

          <v-dialog v-model="modelBtn" width="600" transition="dialog-top-transition">
            <template v-slot:activator="{ props }">
              <v-btn v-bind="props" @click="edit_id=0" icon>
                <v-icon>mdi-account-multiple-plus</v-icon>
              </v-btn>
            </template>
            <template v-slot:default="{ isActive }">
              <v-card>
                <v-toolbar
                  image="https://picsum.photos/1920/1080?random"
                  color="primary"
                  :title="edit_id?'Update Employee Data':'Add Employee'"
                >
                <template v-slot:image>
            <v-img
              gradient="to top right, rgba(19,84,122,.8), rgba(128,208,199,.8)"
            ></v-img>
          </template>
                </v-toolbar>
                <v-card-text>
                    <EmployeeCreate v-if="edit_id==0" @created="alert('success','Employee Created!'),getEmployeeData()" :edit_data="selectedItem" :edit_id="edit_id" @error="alert('error','Something went Wrong!')" @close="isActive.value = false" />
                  <EmployeeCreate v-else-if="edit_id>0" @update="alert('success','Employee date updated!'),getEmployeeData()" :edit_data="selectedItem" :edit_id="edit_id" @error="alert('error','Something went Wrong!')" @close="edit_id=0,isActive.value = false" />
                </v-card-text>
              </v-card>
            </template>
          </v-dialog>



          <v-btn @click="maxWidth = !maxWidth" icon>
            <v-icon v-if="maxWidth">mdi-window-minimize</v-icon>
            <v-icon v-else>mdi-window-maximize</v-icon>
          </v-btn>
        </v-app-bar>

        <v-main id="main" >
          <v-container fluid>
            <v-row dense>
              <v-col v-for="n in employees" :key="n.id" cols="12">
                <v-card>
                  <v-row class="pa-3">
                    <v-col align-self="start" class="pa-0" cols="6" md="4">

                        <img width="130" v-if="n.img" :src="n.img"

                        />
                        <img width="130" v-else src="https://www.w3schools.com/howto/img_avatar.png"

                        />

                        <!-- <v-img
                          :src="n.img" onerror="this.onerror='';this.src='images/profile/profileImg-5.jpg'"
                        ></v-img> -->
                    </v-col>
                    <v-col class="pa-4" align-self="start" cols="6" md="5">
                      <h5>{{ n.name }}</h5>
                      <p>{{ n.email }}</p>
                      <p>{{ n.phone }}</p>
                      <p>{{ n.gender }}</p>
                    </v-col>
                    <v-col class="pa-4 d-flex flex-md-column" cols="12" md="2">
                      <v-btn text class="my-1"
                        ><v-icon color="blue" @click="edit(n)"
                          >mdi-account-edit-outline</v-icon
                        ></v-btn
                      >
                      <v-btn text @click="deleteEmployeeData(n.id)"
                        ><v-icon color="red"
                          >mdi-account-remove-outline</v-icon
                        ></v-btn
                      >
                    </v-col>
                  </v-row>
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-main>
      </v-layout>


    </v-card>


  </v-app>
</template>
<script>
import DataService from '../dataservice.js';
import EmployeeCreate from "./createEmployees.vue";
export default {
  components: { EmployeeCreate },
  data() {
    return {
      search: "",
      searchBtn: false,
      modelBtn:false,
      maxWidth: false,
      loaded: false,
      loading: false,
      alertType:'success',
      alertText:'',
      alertBtn:false,
      employees:[],
      edit_id:0,
      selectedItem:{},

      items: [
        {
          name: "Rohit Sing",
          email: "rojti@gmail.com",
          phone: "9876543222",
          gender: "Male",
          img: "https://cdn.vuetifyjs.com/images/profiles/marcus.jpg",
        },
        {
          name: "Rohit Sing",
          email: "rojti@gmail.com",
          phone: "9876543222",
          gender: "Male",
          img: "https://cdn.vuetifyjs.com/images/profiles/marcus.jpg",
        },
        {
          name: "Rohit Sing",
          email: "rojti@gmail.com",
          phone: "9876543222",
          gender: "Male",
          img: "https://cdn.vuetifyjs.com/images/profiles/marcus.jpg",
        },
        {
          name: "Rohit Sing",
          email: "rojti@gmail.com",
          phone: "9876543222",
          gender: "Male",
          img: "https://cdn.vuetifyjs.com/images/profiles/marcus.jpg",
        },
        {
          name: "Rohit Sing",
          email: "rojti@gmail.com",
          phone: "9876543222",
          gender: "Male",
          img: "https://cdn.vuetifyjs.com/images/profiles/marcus.jpg",
        },
      ],
    };
  },
  watch: {
    search: _.debounce(function () {
        this.getEmployeeData()
    }, 1000),
  },
  created(){
    this.getEmployeeData()
  },

  methods: {

    getEmployeeData(){
        DataService.getEmployees(this.search).then(response=>{
            if(response.data.status=='success'){
                this.employees=response.data.data;
            }
        })
    },
    edit(item){
        this.selectedItem=item;
        this.edit_id=item.id;
        this.modelBtn=true;


    },
    deleteEmployeeData(id){

        DataService.deleteEmployee(id).then(response=>{
            if(response.data.status=='success'){
                this.getEmployeeData();

                this.alert('success','Employee Deleted');
            }
            else{
                this.alert('error','Something went wrong!');
            }
        })
    },

    alert(type,msg){

        this.alertType=type;
      this.alertText=msg;
      this.alertBtn=true;
      setTimeout(()=>{
      this.alertBtn=false
    },2000)
  },



    onClick() {
      this.loading = true;

      setTimeout(() => {
        this.loading = false;
        this.loaded = true;
      }, 2000);
    },
  },
};
</script>
<style scoped>
#main{
    height: 650px;
    overflow-y: scroll;
}
.no_height .v-application__wrap {
  display: unset !important;
    min-height: 0vh !important;
    background-color: #ffffff!important;
  }
  .alert{
    z-index: 10;

  }
</style>

