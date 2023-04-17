<p>Welcome <?php echo $user->name; ?></p>
<p>Your balance is <?php echo $user->balance; ?></p>
<h1>Transfer money<h1>
<form method="post" action="<?php echo base_url('transfer'); ?>">
  <input type="text" name="receiver_id" id="receiver_id" placeholder="Receiver ID">
  <input type="text" name="receiver_name" id="receiver_name" disabled>
  <input type="number" name="amount" placeholder="Amount">
  <button type="submit">Transfer</button>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
  $('#receiver_id').on('change', function() {
    var receiver_id = $(this).val();
    
    $.ajax({
      url: '<?php echo base_url('get_full_name'); ?>',
      type: 'post',
      data: {
        receiver_id: receiver_id,
      },
      success: function(response) {
        $('#receiver_name').val(response);
      },
      error: function() {
        alert('An error occurred while getting full name');
      },
    });
  });
});
</script>

<h2><a href="<?php echo base_url('history');?>">See History</a></h2>