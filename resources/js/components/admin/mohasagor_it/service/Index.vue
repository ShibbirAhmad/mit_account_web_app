<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <button @click="goBack" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i>
          </button>

             <router-link class="btn btn-primary" :to="{ name: 'service_add' }" >
               <i class="fa fa-plus"></i>
             </router-link>

        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Services</li>
        </ol>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-lg-4 col-md-4">
             <router-link :to="{ name: 'service_clients' }" >
              <div class="client_boxs green">
                <h3>
                   Clients
                </h3>
                <i class="fa fa-users client_service_icon" />
              </div>
             </router-link>
          </div>
           <div class="col-lg-4 col-md-4">
              <router-link :to="{ name: 'service_client_add' }" >
              <div class="client_boxs green">
                <h3>
                  Add Client
                </h3>
                <i class="fa fa-user-circle client_service_icon" />
              </div>
               </router-link>
          </div>
            <div class="col-lg-4 col-md-4">
              <router-link :to="{ name: 'service_package_add' }" >
              <div  class="client_boxs green">
                <h3>
                   Add Package
                </h3>
                <i class="fa fa-tasks client_service_icon" />
              </div>
              </router-link>
          </div>
        </div>
        <div class="row justify-content-center">
          <div
            v-for="(service, index) in services"
            :key="index"
            class="col-lg-4 col-md-4 col-xs-12"
          >
            <div class="boxs blue">
              <h3>
                {{ service.name }}
              </h3>
              <router-link :to="{name:'service_details',params:{id:service.id}}" class="boxs-footer"
                >More info <i class="fa fa-arrow-circle-right"></i
              ></router-link>
              <i class="fa fa-folder-open service_icon" />
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>


<script>
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";
Vue.component(HasError.name, HasError);
export default {
  created() {
    this.getServices();
  },
  data() {
    return {
      services: "",
      error: "",
    };
  },

  methods: {
    getServices() {
      axios
        .get("/api/services")
        .then((resp) => {
          console.log(resp);
          this.services = resp.data.services;
        })
    },
    goBack() {
      return window.history.back();
    },
  },
};
</script>

<style   >
.money_icon {
  font-size: 26px;
}
h3 {
  font-size: 20px;
  font-weight: 500;
  font-family: "Poppins";
}
:root {
  --red: hsl(0, 78%, 62%);
  --cyan: hsl(180, 62%, 55%);
  --orange: hsl(34, 97%, 64%);
  --blue: hsl(212, 86%, 64%);
  --varyDarkBlue: hsl(234, 12%, 34%);
  --grayishBlue: hsl(229, 6%, 66%);
  --veryLightGray: hsl(0, 0%, 98%);
  --weight1: 200;
  --weight2: 400;
  --weight3: 600;
}
body {
  font-family: "Poppins", sans-serif;
  background-color: var(--veryLightGray);
}

h1:first-of-type {
  font-weight: var(--weight1);
  color: var(--varyDarkBlue);
}
h1:last-of-type {
  color: var(--varyDarkBlue);
}
@media (max-width: 400px) {
  h1 {
    font-size: 1.5rem;
  }
}

.boxs p {
  color: var(--grayishBlue);
}


.boxs {
  border-radius: 5px;
  box-shadow: 0px 30px 40px -20px var(--grayishBlue);
  padding: 30px;
  margin: 20px 5px;
}
.service_icon {
  float: right;
  font-size: 40px;
  margin-top: -20px;
}


.client_boxs {
  border-radius: 5px;
  box-shadow: 0px 30px 40px -20px var(--grayishBlue);
  padding: 30px;
  margin: 20px;
  height: 100px;
}

.client_boxs>h3{
  margin-top: 0px;
}

.client_service_icon {
  float: right;
  font-size: 40px;
  margin-top: -50px;
}

@media (max-width: 450px) {

  .boxs {
    height: 200px;
  }

}
@media (max-width: 950px) and (min-width: 450px) {
  .boxs {
    text-align: center;
    height: 180px;
  }
}
.cyan {
  border-top: 3px solid var(--cyan);
}
.red {
  border-top: 3px solid var(--red);
}
.blue {
  border-top: 3px solid var(--blue);
}
.green {
  border-top: 3px solid #1abc9c;
}
.orange {
  border-top: 3px solid var(--orange);
}
@media (min-width: 950px) {
  .row1-container {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .row2-container {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .boxs-down {
    position: relative;
    top: 150px;
  }
  .boxs {
    width: 100%;
    height: 150px;
  }
  .header p {
    width: 30%;
  }
}
.boxs {
  border-radius: 5px;
  box-shadow: 0px 30px 40px -20px var(--grayishBlue);
  padding: 30px;
  margin: 20px 5px;
}
</style>