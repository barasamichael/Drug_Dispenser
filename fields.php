<?php
class Field
{
	protected $name;
	protected $label;
	protected $value;
	protected $required;
	protected $readonly;
	protected $disabled;
	protected $type;

	public function __construct(...$kwargs)
	{
		foreach ($kwargs[0] as $key => $value)
		{
			$this->_setAttribute($key, $value);
		}
	}

	private function _getAttribute($attributeName)
	{
		$reflection = new ReflectionClass($this);
		if ($reflection->hasProperty($attributeName))
		{
			$property = $reflection->getProperty($attributeName);
			$property->setAccessible(true);
			return $property->getValue($this);
		}
		else
		{
			return null;
		}
	}

	private function _setAttribute($attributeName, $attributeValue)
	{
		$reflection = new ReflectionClass($this);
		if ($reflection->hasProperty($attributeName))
		{
			$property = $reflection->getProperty($attributeName);
			$property->setAccessible(true);
			$property->setValue($this, $attributeValue);
		}
		else
		{
			return null;
		}
	}

	public function setValue($attributeName, $attributeValue)
	{
		$this->_setAttribute($attributeName, $attributeValue);
	}

	public function getValue()
	{
		return $this->_getAttribute($attributeName);
	}

	public function render()
	{
		$html = "<div class = 'form-group'>";

		// set label
		if (!empty($this->label))
		{
			$html .= "<label for = '" . $this->name . "'>" . $this->label . "</label>";
		}

		$html .= "<input type = '" . $this->getType() . "' name = '" . $this->name . "'" 
			. " class = 'form-control'";
		if (!empty($this->value))
		{
			$html .= " value = '" . $this->value . "'";
		}

		if ($this->required)
		{
			$html .= " required";
		}

		if ($this->readonly)
		{
			$html .= " readonly";
		}

		if ($this->disabled)
		{
			$html .= " disabled";
		}
		$html .= "></div>";

		return $html;
	}

	public function getType()
	{
		return $this->type;
	}
}

class SubmitField extends Field
{
	protected $type = "submit";

	public function render()
	{
		$html = "<div class = 'form-group'>";
		$html .= "<input type = '" . $this->type . "' value = '" . 
			$this->label . "' class = 'btn btn-success'>";
		$html .= "</div>";

		return $html;
	}
}

class IntegerField extends Field
{
	protected $type = 'number';

	public function render()
	{
		$html = '<div class="form-group">';

		if (!empty($this->label)) {
			$html .= '<label for="' . $this->name . '">' . $this->label . '</label>';
		}

		$html .= '<input type="' . $this->type . '" name="' . $this->name . 
			'" class="form-control"';

		if (!empty($this->value)) {
			$html .= ' value="' . $this->value . '"';
		}

		if ($this->required) {
			$html .= ' required';
		}

		if ($this->readonly) {
			$html .= ' readonly';
		}

		if ($this->disabled) {
			$html .= ' disabled';
		}

		$html .= '>';
		$html .= '</div>';

		return $html;
	}
}

class StringField extends Field
{
	protected $type = 'text';

	public function render()
	{
		$html = '<div class="form-group">';

		if (!empty($this->label)) {
			$html .= '<label for="' . $this->name . '">' . $this->label . '</label>';
		}

		$html .= '<input type="' . $this->type . '" name="' . $this->name . 
			'" class="form-control"';

		if (!empty($this->value)) {
			$html .= ' value="' . $this->value . '"';
		}

		if ($this->required) {
			$html .= ' required';
		}

		if ($this->readonly) {
			$html .= ' readonly';
		}

		if ($this->disabled) {
			$html .= ' disabled';
		}

		$html .= '>';
		$html .= '</div>';

		return $html;
	}
}

class TextAreaField extends Field
{
	private $rows;

	public function render()
	{
		$html = '<div class="form-group">';

		if (!empty($this->label)) {
			$html .= '<label for="' . $this->name . '">' . $this->label . '</label>';
		}

		$html .= '<textarea name="' . $this->name . '" class="form-control"';

		if ($this->required) {
			$html .= ' required';
		}

		if ($this->readonly) {
			$html .= ' readonly';
		}

		if ($this->rows){
			$html .= ' rows = "' . $this->rows . '"';
		}

		if ($this->disabled) {
			$html .= ' disabled';
		}

		$html .= '>';

		if (!empty($this->value)) {
			$html .= $this->value;
		}

		$html .= '</textarea>';
		$html .= '</div>';

		return $html;
	}
}

class CheckButtonField extends Field
{
	public function render()
	{
		$html = '<div class="form-check">';

		$html .= '<input type="checkbox" name="' . $this->name . '" class="form-check-input"';

		if (!empty($this->value)) {
			$html .= ' value="' . $this->value . '"';
		}

		if ($this->required) {
			$html .= ' required';
		}

		if ($this->readonly) {
			$html .= ' readonly';
		}

		if ($this->disabled) {
			$html .= ' disabled';
		}

		$html .= '>';

		if (!empty($this->label)) {
			$html .= '<label class="form-check-label" for="' . $this->name . '">' . 
				$this->label . '</label>';
		}

		$html .= '</div>';

		return $html;
	}
}

class SelectField extends Field
{
	protected $options;

	public function __construct($attributes = [])
	{
		parent::__construct($attributes);

		if (isset($attributes['options'])) {
			$this->options = $attributes['options'];
		}
	}

	public function render()
	{
		$html = '<div class="form-group">';

		if (!empty($this->label)) {
			$html .= '<label for="' . $this->name . '">' . $this->label . '</label>';
		}

		$html .= '<select name="' . $this->name . '" class="form-control">';

		foreach ($this->options as $value => $label) {
			$html .= '<option value="' . $value . '"';

			if ($this->value == $value) {
				$html .= ' selected';
			}

			$html .= '>' . $label . '</option>';
		}

		$html .= '</select></div>';

		return $html;
	}
}

class FileField extends Field
{
	public function __construct($attributes = [])
	{
		parent::__construct($attributes);
	}

	public function render()
	{
		$html = '<div class="form-group">';

		if (!empty($this->label)) {
			$html .= '<label for="' . $this->name . '">' . $this->label . '</label>';
		}

		$html .= '<input type="file" name="' . $this->name . '" class="form-control-file"';

		if ($this->required) {
			$html .= ' required';
		}

		$html .= '></div>';

		return $html;
	}
}

class DateField extends Field
{
	public function __construct($attributes = [])
	{
		parent::__construct($attributes);
	}

	public function render()
	{
		$html = '<div class="form-group">';

		if (!empty($this->label)) {
			$html .= '<label for="' . $this->name . '">' . $this->label . '</label>';
		}

		$html .= '<input type="date" name="' . $this->name . '" class="form-control"';

		if (!empty($this->value)) {
			$html .= ' value="' . $this->value . '"';
		}

		if ($this->required) {
			$html .= ' required';
		}

		$html .= '></div>';

		return $html;
	}
}

class FloatField extends Field
{
	public function __construct($attributes = [])
	{
		parent::__construct($attributes);
	}

	public function render()
	{
		$html = '<div class="form-group">';

		if (!empty($this->label)) {
			$html .= '<label for="' . $this->name . '">' . $this->label . '</label>';
		}

		$html .= '<input type="number" name="' . $this->name . '" step="0.01" class="form-control"';

		if (!empty($this->value)) {
			$html .= ' value="' . $this->value . '"';
		}

		if ($this->required) {
			$html .= ' required';
		}

		$html .= '></div>';

		return $html;
	}
}

class DateTimeField extends Field
{
	public function __construct($attributes = [])
	{
		parent::__construct($attributes);
	}

	public function render()
	{
		$html = '<div class="form-group">';

		if (!empty($this->label)) {
			$html .= '<label for="' . $this->name . '">' . $this->label . '</label>';
		}

		$html .= '<input type="datetime-local" name="' . $this->name . '" class="form-control"';

		if (!empty($this->value)) {
			$html .= ' value="' . $this->value . '"';
		}

		if ($this->required) {
			$html .= ' required';
		}

		$html .= '></div>';

		return $html;
	}
}

class RadioButtonField extends Field
{
	protected $choices;

	public function __construct($attributes = [])
	{
		parent::__construct($attributes);

		if (isset($attributes['choices'])) {
			$this->choices = $attributes['choices'];
		}
	}

	public function render()
	{
		$html = '<div class="form-group">';

		if (!empty($this->label)) {
			$html .= '<label for="' . $this->name . '">' . $this->label . '</label>';
		}

		foreach ($this->choices as $value => $label) {
			$html .= '<div class="form-check">';
			$html .= '<input class="form-check-input" type="radio" name="' . $this->name . 
				'" value="' . $value . '"';

			if ($this->value == $value) {
				$html .= ' checked';
			}

			if ($this->required) {
				$html .= ' required';
			}

			$html .= '>';
			$html .= '<label class="form-check-label">' . $label . '</label>';
			$html .= '</div>';
		}

		$html .= '</div>';

		return $html;
	}
}

class PasswordField extends Field
{
	protected $attributes;

	public function __construct($attributes = [])
	{
		$this->attributes = $attributes;
	}

	public function validate($value)
	{
		if (empty($value)) {
			return 'Please enter a password.';
		}
		return true;
	}

	public function render()
	{
		$name = $this->attributes['name'];
		$label = $this->attributes['label'];
		$required = $this->attributes['required'] ? 'required' : '';

		$html = '<div class="form-group">';
		$html .= '<label for="' . $name . '">' . $label . '</label>';
		$html .= '<input type="password" class="form-control" id="' . 
			$name . '" name="' . $name . '" ' . $required . '>';
		$html .= '</div>';

		return $html;
	}
}

class EmailAddressField extends Field
{
	protected $attributes;

	public function __construct($attributes = [])
	{
		$this->attributes = $attributes;
	}

	public function validate($value)
	{
		if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
			return 'Please enter a valid email address.';
		}
		return true;
	}

	public function render()
	{
		$name = $this->attributes['name'];
		$label = $this->attributes['label'];
		$required = $this->attributes['required'] ? 'required' : '';
		$value = isset($this->attributes['value']) ? $this->attributes['value'] : '';

		$html = '<div class="form-group">';
		$html .= '<label for="' . $name . '">' . $label . '</label>';
		$html .= '<input type="email" class="form-control" id="' . $name . '" name="' . $name . '" value="' . $value . '" ' . $required . '>';
		$html .= '</div>';

		return $html;
	}
}
?>
