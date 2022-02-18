<template>
    <div class = "container-fluid display-container">
        <div class="row sub-tabs tabs">
                <p :class="tabs.ongoing? 'active': ''" @click="toggleTab('ongoing')">Ongoing</p>
                <p :class="tabs.ended? 'active': ''" @click="toggleTab('ended')">Ended</p>
        </div>
        <div class="table-top">

            <div v-if="tabs.ongoing">
                <h4>Courses Open For Indicating Interest</h4>
                <p class = "text-muted">Ongoing campaign</p>
            </div>
            <div v-if="tabs.ended">
                <h4>Latest Course Campaign</h4>
                <p class = "text-muted">Overview of latest campaign that has been closed for voting</p>
            </div>
        </div>

        <div class="table-container" v-if="tabs.ongoing">

            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col"></th>
                        <th scope = "col"></th>
                        <th scope = "col"></th>
                        <th scope = "col"></th>
                        <th scope = "col"></th>
                        <th scope = "col" class ="text-center"> 
                            <span class="badge badge-pill close-campaign" @click="closeCampaign()">Close Campaign</span>
                        </th>
                    </tr>
                    <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col"># Current Interested</th>
                        <th scope = "col"># of Campaign Runs</th>
                        <th scope = "col">Vote Start</th>
                        <th scope = "col">Vote End</th>
                        <th scope = "col" class = "status text-center">Campaign Status</th>
                    </tr>
                </thead>
                <tbody v-if="filtered_courses.length > 0">
                    <tr v-for="course in filtered_courses" :key="course">
                        <td><b>{{course.Course_ID}}</b> <br> {{course.Course_Name}}</td>
                        <td>{{course.No_Interest}}</td>
                        <td>{{course.No_Campaigns}}</td>
                        <td><br> {{course.Vote_Start}}</td>
                        <td><br>{{course.Vote_End}}</td>
                        <td class = "text-center">
                            <br><span :class="['badge', 'badge-pill', course.FCourse_Status]" @click="changeStatus(course)" data-toggle="modal" data-target="#exampleModalCenter">{{course.FCourse_Status}}</span>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-container" v-else>

            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col"></th>
                        <th scope = "col"></th>
                        <th scope = "col"></th>
                        <th scope = "col"></th>
                        <th scope = "col"></th>
                        <th scope = "col" class ="text-center"> 
                            <span class="badge badge-pill offer-campaign" @click="offerCampaign()">Offer All</span>
                        </th>
                    </tr>
                     <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col"># Current Interested</th>
                        <th scope = "col"># of Campaign Runs</th>
                        <th scope = "col">Vote Start</th>
                        <th scope = "col">Vote End</th>
                        <th scope = "col" class = "status text-center">Campaign Status</th>
                    </tr>
                </thead>
                <tbody v-if="filtered_courses_closed.length > 0">
                    <tr v-for="course in filtered_courses_closed" :key="course">
                        <td><b>{{course.Course_ID}}</b> <br> {{course.Course_Name}}</td>
                        <td>{{course.No_Interest}}</td>
                        <td>{{course.No_Campaigns}}</td>
                        <td><br> {{course.Vote_Start}}</td>
                        <td><br>{{course.Vote_End}}</td>
                        <td class = "text-center">
                            <br><span :class="['badge', 'badge-pill', course.FCourse_Status]" @click="changeStatus(course)" data-toggle="modal" data-target="#exampleModalCenter">{{course.FCourse_Status}}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" v-if="current!=[]">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.Course_ID}} - {{current.Course_Name}}</h5>
                        <button type="button" id="close" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><b>Course Description:</b></p>
                        <p>{{current.Course_Desc}}</p>  
                        <button type ="button" class="btn btn-success mr-2"  data-dismiss="modal" @click="update('offered')" v-if="current.FCourse_Status != 'offered'">Offer</button>
                        <button type ="button" class="btn btn-danger"  data-dismiss="modal" @click="update('closed')" v-if="current.FCourse_Status == 'ongoing'">Close</button>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import {ONGOINGCAMPAIGN_URL, CLOSECAMPAIGN_URL, LATESTCLOSEDCAMPAIGN_URL, CLOSESINGLECAMPAIGN_URL, OFFERSINGLECAMPAIGN_URL, OFFERCAMPAIGN_URL} from '../../variables'
    export default{
        name: "Campaign",
        data(){
            return{
                filtered_courses:[],
                filtered_courses_closed: [],
                current: [],
                tabs:{
                    ongoing: true,
                    ended: false
                },
            }
        },
        props:{
        },
        methods:{
            toggleTab(tab){
                for (const property in this.tabs){
                    property == tab ? this.tabs[property] = true : this.tabs[property] = false
                }
            },
            getOngoingFilteredCourses(){
                axios.get(ONGOINGCAMPAIGN_URL).then(response => (
                    this.filtered_courses = response.data,
                    console.log(response.data)
                ))
            },
            closeCampaign(){
                axios.get(CLOSECAMPAIGN_URL,
                {withCredentials: true}
                ).then(response => {
                    if (response.data == 'updated'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Latest campaigns closed',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        this.getOngoingFilteredCourses()
                        this.getLatestClosedCampaign()
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong, please try again later',
                            showConfirmButton: false,
                            timer: 1000
                        })
                    }
                    
                })
            },
            offerCampaign(){
                axios.get(OFFERCAMPAIGN_URL,
                {withCredentials: true}
                ).then(response => {
                    if (response.data == 'updated'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Latest campaigns offered',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        this.getOngoingFilteredCourses()
                        this.getLatestClosedCampaign()
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong, please try again later',
                            showConfirmButton: false,
                            timer: 1000
                        })
                    }
                    
                })           
            },
            getLatestClosedCampaign(){
                axios.get(LATESTCLOSEDCAMPAIGN_URL).then(response => (
                    this.filtered_courses_closed = response.data
                ))        
            },
            changeStatus(course){
                this.current = course
            },
            update(status){
                var url = ""
                status == "offered"? url = OFFERSINGLECAMPAIGN_URL: url = CLOSESINGLECAMPAIGN_URL
                axios.post(url,{
                    'course_id': this.current.Course_ID,
                },
                {withCredentials: true}
                ).catch(function(error){
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data == 'updated'){
                        Swal.fire({
                            icon: 'success',
                            html: this.current.Course_Name+ ' ' + status ,
                            showConfirmButton: false,
                            timer: 1000
                        })
                        this.getOngoingFilteredCourses(),
                        this.getLatestClosedCampaign(),
                        document.getElementById('close').click()
                    }else{
                        Swal.fire({
                            icon: 'error',
                            text: 'Something went wrong, please try again later',
                            showConfirmButton: false,
                            timer: 1000
                        })
                    }

                })
            }
        },
        mounted(){
            this.getOngoingFilteredCourses()
            this.getLatestClosedCampaign()
        },
        updated(){
        
            
        }

    }
</script>
<style scoped>
    thead{
        position:sticky;
        top:0;
        background-color: pink;
        
    }
    tr{
        background-color:white;
    }
    .row{
        margin-top: 110px;
        display: flex;
        justify-content: center;
        background-color: rgba(78, 47, 49, 0.767);
        color: white;
        border-radius: 10px;
    }

    .row p:hover {
        color: rgba(70,35,38,255);
    }

    .tabs .active {
        color: rgba(78, 47, 49, 0.767);
    }

    .active {
        border-radius: 10px;
    }

    .sub-tabs{
        margin: 0;
        vertical-align: center;
    }

    .table-container{
        padding: 0px;
    }

    .table-top{
        display:inline-block;
        position: relative;
    }
    

    .badge-pill{
        width: 100px;
        cursor: pointer;
    }
    :deep(.offered){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.closed){
        background-color: var(--pill-red);
        color: white;
    }
    :deep(.ongoing){
        background-color: var(--pill-yellow);
        color: white;
    }
    .close-campaign{
        background-color: var(--lighter-gray);
        color: var(--pill-red);
    }
    .offer-campaign{
        background-color: var(--lighter-gray);
        color: var(--pill-blue);
    }
</style>