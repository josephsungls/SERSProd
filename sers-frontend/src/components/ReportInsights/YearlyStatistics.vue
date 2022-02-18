<template>
    <div>
        <div class = "table-container animate__animated animate__fadeIn animate__faster report-table">
            <div class="table-header">
                <h4>Yearly Statistics</h4>
            </div>
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Year</th>
                        <th scope = "col">No. of Courses Offered</th>
                        <th scope = "col">No. of Runs</th>
                        <th scope = "col">No. of Courses Proposed</th>
                        <th scope = "col">No. of Active Students</th>
                        <th scope = "col">No. of New Sign-ups</th>
                        <th scope = "col">No. of Repeated Sign-ups</th>
                        <th scope = "col">Average No. Of Courses taken per Student</th>
                        <th scope = "col">No Of Courses with Certification</th>
                        <th scope = "col">No Of Students awarded Certification</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="stat in yearlyStats" :key="stat">
                        
                        <template v-if ="years.indexOf(stat.Year1) != -1 || years.indexOf(stat.Year2) != -1">
                            <td>
                                <span v-if="years.indexOf(stat.Year1) != -1">
                                    {{years[years.indexOf(stat.Year1)]}}
                                </span>
                                <span v-else>
                                    {{years[years.indexOf(stat.Year2)]}}
                                </span>
                            </td>
                            <td>
                                <span v-if="stat.Opened_Voting">{{stat.Opened_Voting}}</span>
                                <span v-else>0</span>
                            </td>
                            <td>
                                <span v-if="stat.Opened_Registration">
                                    {{stat.Opened_Registration}}
                                </span>
                                <span v-else>0</span>
                            </td>
                            <td>
                                <span v-if="stat.No_Proposed">
                                    {{stat.No_Proposed}}
                                </span>
                                <span v-else>
                                    0
                                </span>
                            </td>
                            <td>
                                <span v-if="stat.No_Active">
                                    {{stat.No_Active}}
                                </span>
                                <span v-else>0</span>
                            </td>
                            <td>
                                <span v-if="stat.New_Signups">
                                    {{stat.New_Signups}}
                                </span>
                                <span v-else>0</span>
                            </td>
                            <td>
                                {{(stat.No_Active? stat.No_Active : 0) - (stat.New_Signups?stat.New_Signups:0)}}
                            </td>
                            <td>
                                {{stat.Average_Signups?stat.Average_Signups:0}}
                            </td>
                            <td>
                                {{stat.Courses_With_Cert?stat.Courses_With_Cert:0}}
                            </td>
                            <td>
                                {{stat.Certificates_Awarded?stat.Certificates_Awarded:0}}
                            </td>
                        </template>
                    </tr>
                </tbody>
            </table>
        </div>
        <button type="button" class="report-table-btn" @click='exportData()'>Export to .csv</button>
    </div>
</template>
<script>
    import axios from 'axios'
    import {GETYEARLYSTATS_URL} from '../../variables'
    export default{
        name: "YearlyStatistics",
        data(){
            return {
                yearlyStats: []
            }
        },
        props:{
            years: Array,
            trigger: Boolean
        },
        methods: {
            getYearlyStats(){
                axios.get(GETYEARLYSTATS_URL,
                {withCredentials: true}
                ).then(response => (
                    this.yearlyStats = response.data
                ))
            },
            exportData(){
                var ref = this
                const filtered_years = this.yearlyStats.filter(function(row){
                    
                    if (ref.years.indexOf(row.Year1) != -1){
                        
                        return (row)
                    }
                })
                const data = filtered_years.map(row=>({
                    'Year': row.Year1,
                    'No. of Courses Offered	': row.Opened_Voting? row.Opened_Voting : 0,
                    'No. of Runs': row.Opened_Registration? row.Opened_Registration : 0,
                    'No. of Courses Proposed':  row.No_Proposed? row.No_Proposed: 0,
                    'No. of Active Students	': row.No_Active? row.No_Active : 0,
                    'No. of New Sign-ups': row.New_Signups? row.New_Signups : 0,
                    'No. of Repeated Sign-ups': (row.No_Active? row.No_Active : 0) - (row.New_Signups?row.New_Signups:0),
                    'Average No. Of Courses taken per Student': row.Average_Signups? row.Average_Signups:0,
                    'No. of Courses with Certification': row.Courses_With_Cert? row.Courses_With_Cert : 0,
                    'No. of students awarded Certification': row.Certificates_Awarded? row.Certificates_Awarded : 0,
                }))
                // console.log(data)
                const csvText = this.objectToCSV(data)
                // console.log('text', csvText)
                this.downloadCSV(csvText)
            },
            objectToCSV(data){
                const csvRows = []

                const headers = Object.keys(data[0])

                csvRows.push(headers.join(','))


                for (const row of data){
                    const values = headers.map(header=>{
                        const escaped = (''+row[header]).replace(/"/g, '\\"')
                        return `"${escaped}"`
                    })

                    csvRows.push(values.join(','))

                }
   
                return csvRows.join('\n')
            },

            downloadCSV(data){
                const blob = new Blob([data], {type: 'text/csv'})
                const url = window.URL.createObjectURL(blob)
                const a = document.createElement('a')
                a.setAttribute('hidden', '')
                a.setAttribute('href', url)
                a.setAttribute('download', 'Yearly_Statistics'+'.csv' )
                document.body.appendChild(a)
                a.click()
                document.body.removeChild(a)

            }
        },
        mounted(){
            this.getYearlyStats()
            
        },
        watch:{
            trigger (){
                this.exportData() 
            }
        }
    }
</script>
<style scoped>


</style>