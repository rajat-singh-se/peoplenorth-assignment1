<template>
   <v-app class="no_height ">
    <v-form class="ma-4"
    ref="form"
    v-model="valid"
    lazy-validation
  >
  <v-row>
    <v-col cols="12" md="6">
    <v-text-field
      v-model="name"
      variant="underlined"
      :rules="nameRules"
      label="Name"
      required
    ></v-text-field>
</v-col>
<v-col cols="12" md="6">


    <v-text-field
      v-model="email"
      variant="underlined"
      :rules="emailRules"
      label="E-mail"
      required
    ></v-text-field>
</v-col>
</v-row>
<v-row>
<v-col cols="12" md="6">


    <v-text-field
      v-model="phone"
      variant="underlined"
      :rules="[v => !!v || 'Phone number is required',v=>!isNaN(v)||'Phone number should be digit only',v => (v && v.length == 10) || 'Phone number must be 10 digits']"
      label="Phone"
      required
    ></v-text-field>
</v-col>

    <v-col cols="12" md="6">


    <v-select
      v-model="gender"
      variant="underlined"

      :items="['Male','Female']"
      :rules="[v => !!v || 'Gender is required']"
      label="Gender"
      required
    ></v-select>

</v-col>
</v-row>
<v-row>
    <v-col cols="12">
        <input accept="image/*" type="file" @change="selectFile" />
        <!-- <v-file-input
       v-model="file"
        variant="underlined"
    :rules="!edit_id?imgRules:[]"

    accept="image/png, image/jpeg, image/bmp"
    placeholder="Pick an avatar"
    prepend-icon="mdi-camera"
    label="Avatar"
    required
  ></v-file-input> -->
    </v-col>
</v-row>

    <!-- <v-checkbox
      v-model="checkbox"
      :rules="[v => !!v || 'You must agree to continue!']"
      label="Do you agree?"
      required
    ></v-checkbox> -->
<v-row>
    <v-btn
      color="error"
      class="me-4"
      @click="close"
    >
      Cancel
    </v-btn>
    <v-btn
      color="success"
      class="me-4"
      @click="edit_id?update():submit()"
    >
      {{edit_id?'Update':'Submit'}}
    </v-btn>

</v-row>

    <!-- <v-btn
      color="error"
      class="me-4"
      @click="reset"
    >
      Reset Form
    </v-btn>

    <v-btn
      color="warning"
      @click="resetValidation"
    >
      Reset Validation
    </v-btn> -->
  </v-form>

   </v-app>

</template>
<script>
import DataService from '../../dataservice.js';

import { useVuelidate } from '@vuelidate/core'
import { required, email } from '@vuelidate/validators'
export default {
    props:['edit_data','edit_id'],
    data(){
        return{
      valid: true,
      name: '',
      nameRules: [
        v => !!v || 'Name is required',
        v => (v && v.length <= 10) || 'Name must be less than 10 characters',
      ],
      email: '',
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
      imgRules: [
        value => {
          return !value || !value.length || value[0].size < 2000000 || 'Avatar size should be less than 2 MB!'
        },
      ],
      gender: null,
      phone:'',
      items: [
        'Male',
        'Female',

      ],
      file:'',
      checkbox: false,
    }},
    watch:{
        edit_id(){
            if(this.edit_id>0){
                this.setData();
            }

        }
    },
    created(){
        if(this.edit_id>0){
                this.setData();
            }
    },

    methods: {
      async validate () {
        const { valid } = await this.$refs.form.validate()

        if (valid) alert('Form is valid')
      },

      async submit(){
        const { valid } = await this.$refs.form.validate()

        if (valid) {
            const data=new FormData();
            data.append('name',this.name);
            data.append('email',this.email);
            data.append('phone',this.phone);
            data.append('gender',this.gender);
            data.append('img',this.file);

            DataService.createEmployee(data)
            .then(response=>{
                if(response.data.status=='success'){
                    this.$emit('created');
                    this. close();
                }
                else{
                    this.$emit('error');
                }
            })

        }

      },
      async update(){
        if(this.edit_id==0)
        return;
        const { valid } = await this.$refs.form.validate()

        if (valid) {
            const data=new FormData();
            data.append('name',this.name);
            data.append('email',this.email);
            data.append('phone',this.phone);
            data.append('gender',this.gender);
            data.append('img',this.file);

            DataService.updateEmployee(this.edit_id,data)
            .then(response=>{
                if(response.data.status=='success'){
                    this.$emit('update');
                    this. close();
                }
                else{
                    this.$emit('error');
                }
            })

        }

      },

      selectFile(file) {
        console.log(file);
      this.file = file.target.files[0];
    //   this.file = file;
      console.log(this.file);
    },
    setData(){

            this.name=this.edit_data.name;
            this.email=this.edit_data.email;
            this.gender=this.edit_data.gender;
            this.phone=this.edit_data.phone;

    },

      close(){
        this.$refs.form.reset();

        this.$emit('close');


      },
      reset () {
        this.$refs.form.reset()
      },
      resetValidation () {
        this.$refs.form.resetValidation()
      },
    },
  }
</script>
<style >
.no_height .v-application__wrap {
  display: unset !important;
    min-height: 0vh !important;
    background-color: #ffffff!important;
  }
</style>
