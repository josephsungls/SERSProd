<template>
    <div class="container-fluid  display-container">
        <span class='back'>
            <button type='button' @click="goBack()"><i class="fas fa-long-arrow-alt-left"></i></button>
        </span>
        <h4>{{selectedRun.Course_Name}} - Class {{selectedRun.Class}}</h4>
        <table>
            <tr>
                <th>Instructor(s)</th>
                <td>{{processNames(selectedRun.User_Name)}}</td>
            </tr>
            <tr>
                <th>Date Of Course</th>
                <td>{{selectedRun.Run_StartDate}}</td>
            </tr>
            <tr>
                <th>Attendance</th>
                <td>{{selectedRun.No_Registrations}}/{{selectedRun.Run_MaxSlots}}</td>
            </tr>
            <tr>
                <th>Course Level</th>
                <td>{{selectedRun.Category}}</td>
            </tr>
            <tr>
                <th>Selected Questions</th>
                <td>Not working yet</td>
            </tr>
        </table>
        <button type="button" class="btn btn-primary" @click="exportFeedback()">
            Export Feedback to .csv
        </button>
        <div class="table-container">
            <table class = "table">
                <thead>
                    <tr>
                        <template v-for="(question, q_no, idx) in feedbackQuestions" >
                            <th width="200px" scope = "col" class="text-center col-header"  v-if="idx > 0" :key="idx">
  
                                    {{q_no}} <br/>
                                    {{question}}
    
                            </th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="feedback in feedbacks" :key="feedback" @click="viewFeedbacks(course)">
                        <!-- {{course}} -->
                        <template v-for="(response, q_no, idx) in feedback" >
                            <td class="text-center" v-if="idx>3" :key="q_no">
                                
                                    {{response}}
                                
                            </td>
                        </template>

                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import {FEEDBACKQUESTION_URL, FEEDBACKRESPONSE_URL} from '../../variables'
    export default{
        name: 'RunFeedback',
        data(){
            return{
                feedbackQuestions: [],
                feedbacks: [],
                // temporary, get from query
                form_id: 1
            }
        },
        props:{
            selectedRun: Object
        },
        methods:{
            processNames(names){
                return names.split(',').join(', ')
            },
            getQuestions(){
                axios.get(FEEDBACKQUESTION_URL,
                {
                    params: {
                        'form_id': this.form_id
                    },
                    withCredentials: true
                }
                ).catch(function(error){
                    console.log(error)
                    return
                })
                .then(response=>{
                    this.feedbackQuestions = response.data[0]
                    
                })
            },
            getFeedbacks(){
                axios.get(FEEDBACKRESPONSE_URL,
                {
                    params: {
                        'course_id': this.selectedRun.IRCourse_ID,
                        'class': this.selectedRun.IRClass,
                        'run_start': this.selectedRun.IRRun_StartDate,
                    },
                    withCredentials: true
                }
                ).catch(function(error){
                    console.log(error)
                    return
                })
                .then(response=>{
                    this.feedbacks = response.data
                    
                })
            },
            goBack(){
                this.$parent.back()
            },
            exportFeedback(){
                const data = this.feedbacks.map(row=>({
                    Q1: row.CFQ1,
                    Q2: row.CFQ2,
                    Q3: row.CFQ3,
                    Q4: row.CFQ4,
                    Q5: row.CFQ5,
                    Q6: row.CFQ6,
                    Q7: row.CFQ7,
                    Q8: row.CFQ8,
                    Q9: row.CFQ9,
                    Q10: row.CFQ10,
                    Q11: row.CFQ11,
                    Q12: row.CFQ12,
                    Q13: row.CFQ13,
                }))
                const csvText = this.objectToCSV(data)
                // console.log('text', csvText)
                this.downloadCSV(csvText)
            },
            objectToCSV(data){
                const csvRows = []
                // console.log('data', data[0])
                // console.log('header', Object.values(this.feedbackQuestions).slice(1))

                const headers = Object.values(this.feedbackQuestions).slice(1)
                const keys = Object.keys(this.feedbackQuestions).slice(1)   
                // console.log('keys', keys)

                csvRows.push(headers.join(','))
                // console.log(csvRows)

                for (const row of data){
                    const values = keys.map(header=>{
                        const escaped = (''+row[header]).replace(/"/g, '\\"')
                        return `"${escaped}"`
                    })
                    // console.log(csvRows)
                    csvRows.push(values.join(','))
                    // console.log(csvRows)
                    
                }
                // console.log(csvRows.join('\n'))
                return csvRows.join('\n')
            },

            downloadCSV(data){
                const blob = new Blob([data], {type: 'text/csv'})
                const url = window.URL.createObjectURL(blob)
                const a = document.createElement('a')
                a.setAttribute('hidden', '')
                a.setAttribute('href', url)
                a.setAttribute('download', this.selectedRun.Course_ID+'-'+this.selectedRun.Course_Name+ "-" + this.selectedRun.Class + "-" + this.selectedRun.Run_StartDate + '.csv' )
                document.body.appendChild(a)
                a.click()
                document.body.removeChild(a)

            }
        },
        mounted(){
            this.getFeedbacks()
            this.getQuestions()
        }
    }
</script>
<style scoped>
    .col-header{
        width:200px;
    }
</style>