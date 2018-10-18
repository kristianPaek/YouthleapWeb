package com.android.widget.slidingtab;

import java.util.ArrayList;

import android.content.Context;
import android.util.AttributeSet;
import android.util.Log;
import android.view.MotionEvent;
import android.view.VelocityTracker;
import android.view.View;
import android.view.ViewConfiguration;
import android.view.ViewParent;
import android.widget.FrameLayout;
import android.widget.OverScroller;


public class ViewSlider extends FrameLayout {

    private static final String TAG = "ViewSlider";

    private static final int MIN_VELOCITY = 500;
    private static final int INVALID = -1;

    private OverScroller mScroller;
    private VelocityTracker mVelocityTracker;

    private int mTouchSlop;
    private int mMaximumVelocity;
    private float mLastMotionX;
    private float mLastMotionY;

    private boolean mIsBeingDragged = false;
    private int mOverscrollDistance;
    private int mOverflingDistance;

    /**
     * ID of the active pointer. This is used to retain consistency during
     * drags/flings if multiple pointers are used.
     */
    private int mActivePointerId = INVALID_POINTER;

    /**
     * Sentinel value for no current active pointer.
     * Used by {@link #mActivePointerId}.
     */
    private static final int INVALID_POINTER = -1;

    private int mCurrentPosition;
    private int mNextPosition = INVALID;
    private int mLastScrollDirection;
    private boolean mNeedScroll = false;

    private View[] mVisibleViews;
    private int mVisibleCount;
    private OnViewSlideListener mListener;
    private ArrayList<String> mTitles = new ArrayList<String>();

    public ViewSlider(Context context) {
        super(context);
        init(context);
    }

    public ViewSlider(Context context, AttributeSet attrs) {
        super(context, attrs);
        init(context);
    }


    public ViewSlider(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        init(context);
    }

    private void init(Context context) {
        mScroller = new OverScroller(context);
        setFocusable(true);
        setDescendantFocusability(FOCUS_AFTER_DESCENDANTS);
        setWillNotDraw(false);

        final ViewConfiguration configuration = ViewConfiguration.get(context);

        mTouchSlop = configuration.getScaledTouchSlop();
        mOverscrollDistance = configuration.getScaledOverscrollDistance();
        mOverflingDistance = configuration.getScaledOverflingDistance();
        mMaximumVelocity = configuration.getScaledMaximumFlingVelocity();

        mVisibleCount = 0;
        mVisibleViews = null;
    }

    public void setOnViewSlideListener(OnViewSlideListener listener) {
        mListener = listener;
    }

    public int getVisibleCount() {
        return mVisibleCount;
    }

    public void addTitle(int titleid) {
        mTitles.add(getContext().getString(titleid));
    }

    public ArrayList<String> getTitles() {
        return mTitles;
    }

    public void setCurrentPosition(int position) {
        if (!mScroller.isFinished()) {
            mScroller.abortAnimation();
        }

        mCurrentPosition = position;
        mNextPosition = INVALID;
        mLastScrollDirection = 0;

        final int width = getWidth();
        if (width == 0) {
            mNeedScroll = true;
        } else {
            scrollTo(width * position, getScrollY());
        }

        if (mListener != null) {
            mListener.onViewSelected(mCurrentPosition);
        }
    }

    public void setCurrentItem(int position) {
        scrollToPosition(position);
    }

    public int getCurrentItem() {
        return mCurrentPosition;
    }

    @Override
    protected void onMeasure(int widthMeasureSpec, int heightMeasureSpec) {
        super.onMeasure(widthMeasureSpec, heightMeasureSpec);
    }

    @Override
    protected void onLayout(boolean changed, int l, int t, int r, int b) {
        int childLeft = 0;
        int width = getWidth();
        int height = getHeight();
        final int count = getChildCount();

        mVisibleCount = 0;
        mVisibleViews = null;
        if (count > 0) {
            mVisibleViews = new View[count];
            for (int i = 0; i < count; i++) {
                final View child = getChildAt(i);
                if (child.getVisibility() != View.GONE) {
                    mVisibleViews[mVisibleCount] = child;
                    final int childWidth = child.getMeasuredWidth();
                    final int childHeight = child.getMeasuredHeight();
                    child.layout(childLeft, (height - childHeight) / 2, childLeft + childWidth, (height + childHeight) / 2);
                    childLeft += childWidth;
                    mVisibleCount++;
                }
            }

            if (mNeedScroll) {
                mNeedScroll = false;
                super.scrollTo(width * mCurrentPosition, getScrollY());
            } else {
                int scrollX = getScrollX();
                if (scrollX + width > childLeft) {
                    super.scrollTo(childLeft - width, getScrollY());
                }
            }
        }
    }

    @Override
    public void requestDisallowInterceptTouchEvent(boolean disallowIntercept) {
        if (disallowIntercept) {
            recycleVelocityTracker();
        }
        super.requestDisallowInterceptTouchEvent(disallowIntercept);
    }

    @Override
    public boolean onInterceptTouchEvent(MotionEvent ev) {
        /*
         * This method JUST determines whether we want to intercept the motion.
         * If we return true, onMotionEvent will be called and we do the actual
         * scrolling there.
         */

        /*
        * Shortcut the most recurring case: the user is in the dragging
        * state and he is moving his finger.  We want to intercept this
        * motion.
        */
        final int action = ev.getAction();
        if ((action == MotionEvent.ACTION_MOVE) && (mIsBeingDragged)) {
            return true;
        }

        switch (action & MotionEvent.ACTION_MASK) {
            case MotionEvent.ACTION_MOVE: {
                /*
                 * mIsBeingDragged == false, otherwise the shortcut would have caught it. Check
                 * whether the user has moved far enough from his original down touch.
                 */

                /*
                * Locally do absolute value. mLastMotionX is set to the x value
                * of the down event.
                */
                final int activePointerId = mActivePointerId;
                if (activePointerId == INVALID_POINTER) {
                    // If we don't have a valid id, the touch down wasn't on content.
                    break;
                }

                final int pointerIndex = ev.findPointerIndex(activePointerId);
                if (pointerIndex == -1) {
                    Log.e(TAG, "Invalid pointerId=" + activePointerId + " in onInterceptTouchEvent");
                    break;
                }

                final int x = (int) ev.getX(pointerIndex);
                final int y = (int) ev.getY(pointerIndex);
                final int xDiff = (int) Math.abs(x - mLastMotionX);
                final int yDiff = (int) Math.abs(y - mLastMotionY);
                if (xDiff > mTouchSlop && yDiff < xDiff) {
                    mIsBeingDragged = true;
                    mLastMotionX = x;
                    mLastMotionY = y;
                    initVelocityTrackerIfNotExists();
                    mVelocityTracker.addMovement(ev);
                    if (getParent() != null) getParent().requestDisallowInterceptTouchEvent(true);
                }
                break;
            }

            case MotionEvent.ACTION_DOWN: {
                final int x = (int) ev.getX();
                final int y = (int) ev.getY();
//                if (!inChild((int) x, (int) ev.getY())) {
//                    mIsBeingDragged = false;
//                    recycleVelocityTracker();
//                    break;
//                }

                /*
                 * Remember location of down touch.
                 * ACTION_DOWN always refers to pointer index 0.
                 */
                mLastMotionX = x;
                mLastMotionY = y;
                mActivePointerId = ev.getPointerId(0);

                initOrResetVelocityTracker();
                mVelocityTracker.addMovement(ev);

                /*
                * If being flinged and user touches the screen, initiate drag;
                * otherwise don't.  mScroller.isFinished should be false when
                * being flinged.
                */
                mIsBeingDragged = !mScroller.isFinished();
                break;
            }

            case MotionEvent.ACTION_CANCEL:
            case MotionEvent.ACTION_UP:
                /* Release the drag */
                mIsBeingDragged = false;
                mActivePointerId = INVALID_POINTER;
                if (mScroller.springBack(getScrollX(), getScrollY(), 0, getScrollRange(), 0, 0)) {
                    postInvalidateOnAnimation();
                }
                break;
            case MotionEvent.ACTION_POINTER_DOWN: {
                final int index = ev.getActionIndex();
                mLastMotionX = (int) ev.getX(index);
                mLastMotionY = (int) ev.getY(index);
                mActivePointerId = ev.getPointerId(index);
                break;
            }
            case MotionEvent.ACTION_POINTER_UP:
                onSecondaryPointerUp(ev);
                mLastMotionX = (int) ev.getX(ev.findPointerIndex(mActivePointerId));
                mLastMotionY = (int) ev.getY(ev.findPointerIndex(mActivePointerId));
                break;
        }

        /*
        * The only time we want to intercept motion events is if we are in the
        * drag mode.
        */
        return mIsBeingDragged;
    }

    @Override
    public boolean onTouchEvent(MotionEvent ev) {
        initVelocityTrackerIfNotExists();
        mVelocityTracker.addMovement(ev);

        final int action = ev.getAction();

        switch (action & MotionEvent.ACTION_MASK) {
        case MotionEvent.ACTION_DOWN: {
            if (getChildCount() == 0) {
                return false;
            }
            if ((mIsBeingDragged = !mScroller.isFinished())) {
                final ViewParent parent = getParent();
                if (parent != null) {
                    parent.requestDisallowInterceptTouchEvent(true);
                }
            }

            /*
             * If being flinged and user touches, stop the fling. isFinished
             * will be false if being flinged.
             */
            if (!mScroller.isFinished()) {
                mScroller.abortAnimation();
            }

            // Remember where the motion event started
            mLastMotionX = ev.getX();
            mLastMotionY = ev.getY();
            mActivePointerId = ev.getPointerId(0);
            break;
        }
        case MotionEvent.ACTION_MOVE:
        	final int activePointerIndex = ev.findPointerIndex(mActivePointerId);
            if (activePointerIndex == -1) {
                Log.e(TAG, "Invalid pointerId=" + mActivePointerId + " in onTouchEvent");
                break;
            }

            final int x = (int) ev.getX(activePointerIndex);
            final int y = (int) ev.getY(activePointerIndex);
            int deltaX = (int) (mLastMotionX - x);
            int deltaY = (int) (mLastMotionY - y);
            if (!mIsBeingDragged && Math.abs(deltaX) > mTouchSlop &&  Math.abs(deltaY) <  Math.abs(deltaX)) {
                final ViewParent parent = getParent();
                if (parent != null) {
                    parent.requestDisallowInterceptTouchEvent(true);
                }
                mIsBeingDragged = true;
                if (deltaX > 0) {
                    deltaX -= mTouchSlop;
                } else {
                    deltaX += mTouchSlop;
                }
            }

            if (mIsBeingDragged) {
                // Scroll to follow the motion event
                mLastMotionX = x;
                mLastMotionY = y;

                final int oldX = getScrollX();
                final int oldY = getScrollY();
                final int range = getScrollRange();

                overScrollBy(deltaX, 0, getScrollX(), 0, range, 0, mOverscrollDistance, 0, true);
                onScrollChanged(getScrollX(), getScrollY(), oldX, oldY);
            }
            break;
        case MotionEvent.ACTION_UP:
            if (mIsBeingDragged) {
                final VelocityTracker velocityTracker = mVelocityTracker;
                velocityTracker.computeCurrentVelocity(1000, mMaximumVelocity);
                int velocityX = (int) velocityTracker.getXVelocity();
                final int width = getWidth();
                int position = getScrollX() / width;

                if (velocityX > MIN_VELOCITY) {
                    if (position >= 0) {
                        // Fling hard enough to move left
                        scrollToPosition(position);
                    } else {
                        scrollToSlot();
                    }
                } else if (velocityX < -MIN_VELOCITY) {
                    if (position < mVisibleCount - 1) {
                        // Fling hard enough to move right
                        scrollToPosition(position + 1);
                    } else {
                        scrollToSlot();
                    }
                } else {
                    scrollToSlot();
                }

                recycleVelocityTracker();
            }
            mActivePointerId = INVALID_POINTER;
            mIsBeingDragged = false;
            break;
        case MotionEvent.ACTION_CANCEL:
            if (mIsBeingDragged && getChildCount() > 0) {
                if (mScroller.springBack(getScrollX(), getScrollY(), 0, getScrollRange(), 0, 0)) {
                    invalidate();
                }
                mActivePointerId = INVALID_POINTER;
                mIsBeingDragged = false;
            }
            recycleVelocityTracker();
            break;
        case MotionEvent.ACTION_POINTER_UP:
            onSecondaryPointerUp(ev);
            break;
        }
        return true;
    }

    @Override
    protected void onOverScrolled(int scrollX, int scrollY, boolean clampedX, boolean clampedY) {
        super.onOverScrolled(scrollX, scrollY, clampedX, clampedY);
        // Treat animating scrolls differently; see #computeScroll() for why.
        if (!mScroller.isFinished()) {
            scrollTo(scrollX, scrollY);
            if (clampedX) {
                mScroller.springBack(scrollX, scrollY, 0, getScrollRange(), 0, 0);
            }
        } else {
            scrollTo(scrollX, scrollY);
        }
    }

    @Override
    public void computeScroll() {
        if (mScroller.computeScrollOffset()) {
            int oldX = getScrollX();
            int oldY = getScrollY();
            int x = mScroller.getCurrX();
            int y = mScroller.getCurrY();

            if (oldX != x || oldY != y) {
                final int range = getScrollRange();
                overScrollBy(x - oldX, y - oldY, oldX, oldY, range, 0, mOverflingDistance, 0, false);
                onScrollChanged(getScrollX(), getScrollY(), oldX, oldY);
            }

            // Keep on drawing until the animation has finished.
            postInvalidate();
        } else if (mNextPosition != INVALID) {
            mCurrentPosition = Math.max(0, Math.min(mNextPosition, getChildCount() - 1));
            mNextPosition = INVALID;

            if (mLastScrollDirection != 0) {
                if (mListener != null) {
                    mListener.onViewSelected(mCurrentPosition);
                }
            }
        }
    }

    @Override
    protected void onScrollChanged(int l, int t, int oldl, int oldt) {
        super.onScrollChanged(l, t, oldl, oldt);

        if (mListener != null) {
            final int width = getWidth();
            final int position = l / width;
            float offset = (float) (l % width) / (float) width;
            mListener.onViewScrolled(position, offset);
        }
    }

    private void initOrResetVelocityTracker() {
        if (mVelocityTracker == null) {
            mVelocityTracker = VelocityTracker.obtain();
        } else {
            mVelocityTracker.clear();
        }
    }

    private void initVelocityTrackerIfNotExists() {
        if (mVelocityTracker == null) {
            mVelocityTracker = VelocityTracker.obtain();
        }
    }

    private void recycleVelocityTracker() {
        if (mVelocityTracker != null) {
            mVelocityTracker.recycle();
            mVelocityTracker = null;
        }
    }

    private void onSecondaryPointerUp(MotionEvent ev) {
        final int pointerIndex = (ev.getAction() & MotionEvent.ACTION_POINTER_INDEX_MASK) >>
                MotionEvent.ACTION_POINTER_INDEX_SHIFT;
                final int pointerId = ev.getPointerId(pointerIndex);
                if (pointerId == mActivePointerId) {
                    final int newPointerIndex = pointerIndex == 0 ? 1 : 0;
                    mLastMotionX = ev.getX(newPointerIndex);
                    mLastMotionY = ev.getY(newPointerIndex);
                    mActivePointerId = ev.getPointerId(newPointerIndex);
                }
    }

    private int getScrollRange() {
        int scrollRange = 0;
        int childCount = mVisibleCount;
        if (childCount > 0) {
            View child = mVisibleViews[childCount-1];
            scrollRange = Math.max(0, child.getRight() - (getWidth() - getPaddingLeft() - getPaddingRight()));
        }
        return scrollRange;
    }

    private void scrollToSlot() {
        final int width = getWidth();
        scrollToPosition((getScrollX() + width / 2) / width);
    }

    private void scrollToPosition(int position) {
        if (!mScroller.isFinished())
            return;

        mLastScrollDirection = position - mCurrentPosition;
        position = Math.max(0, Math.min(position, getChildCount() - 1));
        mNextPosition = position;

        final int newX = position * getWidth();
        final int deltaX = newX - getScrollX();
        mScroller.startScroll(getScrollX(), getScrollY(), deltaX, 0, Math.min(200, Math.abs(deltaX)));
        invalidate();
    }

    public static interface OnViewSlideListener {
        public void onViewScrolled(int position, float offset);
        public void onViewSelected(int position);
    }

}
