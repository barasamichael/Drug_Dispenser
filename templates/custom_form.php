<?php
class CustomForm
{
	private $fields = [];

	public function addField(Field $field)
	{
		$this->fields[] = $field;
	}

	public function render()
	{
		echo "<form method = 'POST'>";
		foreach ($this->fields as $field)
		{
			echo $field->render();
		}
		echo "</form>";
	}
}
?>
