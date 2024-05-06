<?php
$query = "SELECT
v.*,
COUNT(e.event_id) AS total_in_use
FROM
venue v
LEFT JOIN
events e ON v.v_id = e.v_id 
GROUP BY
v.v_id, v.venue
ORDER BY total_in_use DESC;
";
$result = $conn->query($query);

$venues_result = [];

while ($venue = $result->fetch_assoc()) {
    $venues_result[] = $venue;
}



?>
<script>
    function updateVenue(venue, v_id) {
        var new_venue = window.prompt("Update venue \n\n!Note: Updating the venue will affect events that use it.", venue);
        if (!new_venue || new_venue == '') {
            return;
        }
        if (new_venue != venue) {
            $.ajax({
                type: "POST",
                url: "../backend/update/update_venue.php",
                data: {
                    venue: new_venue,
                    v_id: v_id
                },
                success: function(response) {
                    if (response == '1') {
                        window.location = '';
                    }
                }
            });
        }

    }

    function deleteVenue(v_id) {


        if (confirm('Do you really want to delete the venue?')) {
            $.ajax({
                type: "POST",
                url: "../backend/delete/delete_venue.php",
                data: {
                    v_id: v_id
                },
                success: function(response) {
                    window.location = '';

                }
            });
        }

    }
</script>

<div class="table-container w-full overflow-auto mb-40 p-8 border">
    <table class="w-full min-w-[34rem] " id="pending-tbl">
        <thead>
            <tr>
                <th class="text-start border border-green-800 font-semibold py-4 px-3  text-white bg-main text-base md:text-lg">Venue
                </th>
                <th class="text-start border border-green-800 font-semibold py-4 px-3  text-white bg-main text-base md:text-lg">Total in
                    use</th>

                <th class="text-center border border-green-800 font-semibold py-2 px-3  text-white bg-main text-base md:text-lg">
                    Action</th>
            </tr>
        </thead>

        <tbody>

        </tbody>




    </table>
    <script>
        let venueData = <?= json_encode($venues_result) ?>;
        $('#pending-tbl').DataTable({
            data: venueData,
            ordering: false,
            paging: true,
            pageLength: 10,
            info: true,
            "pagingType": "simple_numbers",
            columns: [{
                    data: 'venue',
                    className: 'py-2 px-3 border text-start text-sm md:text-base'
                },
                {
                    data: 'total_in_use',
                    className: 'py-2 px-3 border !text-start text-sm md:text-base'
                },
                {
                    data: null,
                    className: 'border whitespace-nowrap !py-5 !px-2 !text-center',
                    render: function(data, type, row) {
                        let buttons = '';

                        let v_id = data.v_id;
                        let venue = data.venue;
                        let created_by = data.created_by;
                        let creator_access = data.creator_access;
                        let total_in_use = data.total_in_use;

                        console.log(v_id)
                        console.log(created_by)
                        console.log(creator_access)

                        buttons += `
                        <button onclick="updateVenue('${venue}',${v_id})" class="px-6 py-2 mx-auto self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400 mr-6 cursor-pointer transition-default text-white font-semibold rounded-xl" id="upt-btn">
                            Edit
                        </button>
                        `;

                        if (total_in_use > 0) {
                            buttons += `<button type="button" disabled class="px-6 py-2 mx-auto self-end md:text-base text-sm  bg-red-700  opacity-30 transition-default text-white font-semibold rounded-xl" id="upt-btn">Delete</button>`;
                        } else {
                            buttons += ` <button type="button" onclick="deleteVenue(${v_id})" class="px-6 py-2 mx-auto self-end md:text-base text-sm  bg-red-700 hover:bg-red-600 cursor-pointer   transition-default text-white font-semibold rounded-xl" id="upt-btn">Delete</button>
                    `;
                        }


                        return `${buttons}
                                    `;
                    }
                },
            ],

            rowCallback: function(row, data, index) {
                if (index % 2 === 0) {
                    $(row).addClass('bg-gray-50');
                }
                $(row).addClass('hover:bg-gray-100');

            }
        });


        function checkConflict(start_datetime, end_datetime, v_id, to_submit) {

            $.ajax({
                type: "POST",
                url: "../backend/validator/check_conflict.php",
                data: {
                    start_datetime: start_datetime,
                    end_datetime: end_datetime,
                    v_id: v_id

                },
                success: function(response) {
                    if (response == 'false') {
                        if (confirm('Do you really want to approve the event?')) {
                            $('#' + to_submit).submit();
                        }
                    } else {


                        alert('The venue is not available on that date/time');
                    }
                }
            });
        }
    </script>
</div>