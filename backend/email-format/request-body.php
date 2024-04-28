<p style="font-size: 1.1rem;"><b>Title:</b> <?= $title ?></p>
<p style="font-size: 1.1rem;"><b>Description:</b> <?= $description ?></p>
<p style="font-size: 1.1rem;"><b>Venue:</b> <?= $venue ?></p>
<p style="font-size: 1.1rem;"><b>Start:</b> <?= date('F j Y h:iA', strtotime($start_datetime)) ?></p>
<p style="font-size: 1.1rem;"><b>End:</b> <?= date('F j Y h:iA', strtotime($end_datetime)) ?></p>
<form action="http://localhost:8080/ems2/backend/update/approve_event.php" method="POST">
    <input type="hidden" name="event_id" value="<?= $event_id ?>">
    <input type="hidden" name="status" value="approved">
    <input type="hidden" name="from" value="gmail">
    <button  style="display: block;  font-family: Arial, Helvetica, sans-serif; text-align:center;  font-size: 1.2rem; background-color: #2E6B45; width: 8rem; padding: 4px 30px; border:none; outline:none; border-radius: 6px; white-space: nowrap; color: white; text-decoration: none; text-align: center; cursor: pointer;">Approve </button>
</form>