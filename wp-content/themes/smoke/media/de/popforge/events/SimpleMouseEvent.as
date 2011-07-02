package de.popforge.events
{
	import flash.events.Event;
	
	public class SimpleMouseEvent extends Event
	{
		static public const PRESS: String = 'onPress';
		static public const RELEASE: String = 'onRelease';
		static public const RELEASE_OUTSIDE: String = 'onReleaseOutside';
		static public const ROLL_OVER: String = 'onRollOver';
		static public const ROLL_OUT: String = 'onRollOut';
		static public const DRAG_OVER: String = 'onDragOver';
		static public const DRAG_OUT: String = 'onDragOut';
		
		public function SimpleMouseEvent( type: String, bubbles: Boolean = true, cancelable: Boolean = false  )
		{
			super( type, bubbles, cancelable );
		}
	}
}