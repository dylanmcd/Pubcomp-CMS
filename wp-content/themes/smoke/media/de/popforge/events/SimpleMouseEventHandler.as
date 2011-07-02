package de.popforge.events
{
	import flash.display.InteractiveObject;
	import flash.display.MovieClip;
	import flash.display.Sprite;
	import flash.display.Stage;
	import flash.events.MouseEvent;
	import flash.utils.Dictionary;
	
	public class SimpleMouseEventHandler
	{
		static public function register( target: InteractiveObject, trackAsMenu: Boolean = false ): void
		{
			new SimpleMouseEventHandler( target, trackAsMenu );
		}
		
		static public function unregister( target: InteractiveObject ): void
		{
			SimpleMouseEventHandler( table[ target ] ).dispose();
			
			delete table[ target ];
		}
		
		static private const table: Dictionary = new Dictionary( true );
		
		//-- target must have set buttonMode = true
		private var target: InteractiveObject;
		
		private var trackAsMenu: Boolean;
		
		public function SimpleMouseEventHandler( target: InteractiveObject, trackAsMenu: Boolean )
		{
			this.target = target;
			this.trackAsMenu = trackAsMenu;
			
			init();
		}
		
		private function init(): void
		{
			if( target is Sprite )
				Sprite( target ).buttonMode = true;

			else if( target is MovieClip )
				MovieClip( target ).buttonMode = true;
			
			target.addEventListener( MouseEvent.MOUSE_OVER, onTargetMouseOver );
			target.addEventListener( MouseEvent.MOUSE_OUT, onTargetMouseOut );
			target.addEventListener( MouseEvent.MOUSE_DOWN, onTargetMouseDown );
			target.addEventListener( MouseEvent.MOUSE_UP, onTargetMouseUp );
			
			table[ target ] = this;
		}
		
		private function dispose(): void
		{
			target.removeEventListener( MouseEvent.MOUSE_OVER, onTargetMouseOver );
			target.removeEventListener( MouseEvent.MOUSE_OUT, onTargetMouseOut );
			target.removeEventListener( MouseEvent.MOUSE_DOWN, onTargetMouseDown );
			target.removeEventListener( MouseEvent.MOUSE_UP, onTargetMouseUp );
		}
		
		private function onTargetMouseOver( event: MouseEvent ): void
		{
			if( event.buttonDown )
			{
				if( target.stage.focus == target || trackAsMenu )
					dispatch( SimpleMouseEvent.DRAG_OVER );
			}
			else
			{
				dispatch( SimpleMouseEvent.ROLL_OVER );
			}
		}
		
		private function onTargetMouseOut( event: MouseEvent ): void
		{
			if( event.buttonDown )
			{
				if( target.stage != null )
				{
					if( target.stage.focus == target || trackAsMenu )
						dispatch( SimpleMouseEvent.DRAG_OUT );
				}
			}
			else
			{
				dispatch( SimpleMouseEvent.ROLL_OUT );
			}
		}
		
		private function onTargetMouseDown( event: MouseEvent ): void
		{
			dispatch( SimpleMouseEvent.PRESS );
			
			target.stage.addEventListener( MouseEvent.MOUSE_UP, onStageMouseUp );
		}
		
		private function onTargetMouseUp( event: MouseEvent ): void
		{
			if( target.stage.focus == target )
				dispatch( SimpleMouseEvent.RELEASE );
				
			target.stage.removeEventListener( MouseEvent.MOUSE_UP, onStageMouseUp );
		}
		
		private function onStageMouseUp( event: MouseEvent ): void
		{
			dispatch( SimpleMouseEvent.RELEASE_OUTSIDE );
			
			Stage( event.currentTarget ).removeEventListener( MouseEvent.MOUSE_UP, onStageMouseUp );
		}
		
		private function dispatch( type: String ): void
		{
			target.dispatchEvent( new SimpleMouseEvent( type ) );
		}
	}
}